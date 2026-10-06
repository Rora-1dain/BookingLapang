import { useEffect, useRef, useState } from 'react'
import { fetchFotoLapangan, unggahFotoLapangan, hapusFoto, jadikanFotoUtama } from '../api/foto'

const MAX_FOTO = 8
const MAX_UKURAN = 2 * 1024 * 1024 // 2MB
const FORMAT_OK = ['jpg', 'jpeg', 'png']

function ekstensi(nama) {
  return String(nama).split('.').pop()?.toLowerCase()
}

/**
 * Pengelola foto lapangan.
 *
 * - Mode EDIT (`lapanganId` diisi): foto langsung diunggah/dihapus ke server.
 * - Mode BUAT (`lapanganId` kosong): file hanya disimpan lokal (preview),
 *   lalu diunggah oleh pemanggil setelah lapangan berhasil dibuat.
 */
export default function FotoLapanganManager({
  lapanganId,
  files = [],
  onChangeFiles,
  disabled = false,
}) {
  const modeEdit = Boolean(lapanganId)
  const inputRef = useRef(null)

  const [fotos, setFotos] = useState([]) // foto tersimpan (mode edit)
  const [loading, setLoading] = useState(modeEdit)
  const [busy, setBusy] = useState(false)
  const [error, setError] = useState(null)
  const [msg, setMsg] = useState(null)
  const [previews, setPreviews] = useState([]) // preview lokal (mode buat)

  // Buat object URL untuk file yang belum diunggah, dan bersihkan saat
  // berubah/unmount. Dibuat di effect (bukan useMemo) supaya aman terhadap
  // React StrictMode double-invoke — URL tidak dicabut sebelum dipakai.
  useEffect(() => {
    if (modeEdit) {
      setPreviews([])
      return undefined
    }
    const list = files.map((f) => ({ file: f, url: URL.createObjectURL(f) }))
    setPreviews(list)
    return () => list.forEach((p) => URL.revokeObjectURL(p.url))
  }, [files, modeEdit])

  useEffect(() => {
    if (!modeEdit) return
    let batal = false
    setLoading(true)
    fetchFotoLapangan(lapanganId)
      .then((res) => !batal && setFotos(res.data ?? []))
      .catch((err) => !batal && setError(err.message))
      .finally(() => !batal && setLoading(false))
    return () => {
      batal = true
    }
  }, [lapanganId, modeEdit])

  const jumlahTotal = modeEdit ? fotos.length : files.length
  const penuh = jumlahTotal >= MAX_FOTO

  function validasiFile(list) {
    for (const f of list) {
      if (!FORMAT_OK.includes(ekstensi(f.name))) {
        return `Format ${f.name} harus JPG/PNG.`
      }
      if (f.size > MAX_UKURAN) {
        return `${f.name} melebihi 2MB.`
      }
    }
    if (jumlahTotal + list.length > MAX_FOTO) {
      return `Maksimal ${MAX_FOTO} foto per lapangan.`
    }
    return null
  }

  async function handlePilih(e) {
    const terpilih = Array.from(e.target.files ?? [])
    if (inputRef.current) inputRef.current.value = ''
    if (terpilih.length === 0) return

    setError(null)
    setMsg(null)
    const salah = validasiFile(terpilih)
    if (salah) {
      setError(salah)
      return
    }

    if (!modeEdit) {
      onChangeFiles?.([...files, ...terpilih])
      return
    }

    setBusy(true)
    try {
      const res = await unggahFotoLapangan(lapanganId, terpilih)
      setFotos(res.data ?? [])
      setMsg('Foto berhasil diunggah.')
    } catch (err) {
      setError(err.message)
    } finally {
      setBusy(false)
    }
  }

  function handleHapusLokal(idx) {
    onChangeFiles?.(files.filter((_, i) => i !== idx))
  }

  async function handleHapusServer(foto) {
    setBusy(true)
    setError(null)
    setMsg(null)
    try {
      await hapusFoto(foto.id)
      const res = await fetchFotoLapangan(lapanganId)
      setFotos(res.data ?? [])
      setMsg('Foto dihapus.')
    } catch (err) {
      setError(err.message)
    } finally {
      setBusy(false)
    }
  }

  async function handleUtama(foto) {
    setBusy(true)
    setError(null)
    setMsg(null)
    try {
      await jadikanFotoUtama(foto.id)
      const res = await fetchFotoLapangan(lapanganId)
      setFotos(res.data ?? [])
      setMsg('Foto utama diperbarui.')
    } catch (err) {
      setError(err.message)
    } finally {
      setBusy(false)
    }
  }

  return (
    <div>
      <div className="flex items-center justify-between mb-1">
        <span className="block text-[11px] font-bold tracking-wider text-ink">
          FOTO LAPANGAN
        </span>
        <span className="text-[11px] text-muted">
          {jumlahTotal}/{MAX_FOTO}
        </span>
      </div>

      {loading ? (
        <p className="text-[12px] text-muted py-3">Memuat foto...</p>
      ) : (
        <div className="grid grid-cols-3 sm:grid-cols-4 gap-2">
          {modeEdit
            ? fotos.map((f) => (
                <FotoTile
                  key={f.id}
                  url={f.url}
                  utama={f.is_utama}
                  disabled={disabled || busy}
                  onUtama={() => handleUtama(f)}
                  onHapus={() => handleHapusServer(f)}
                />
              ))
            : previews.map((p, i) => (
                <FotoTile
                  key={p.url}
                  url={p.url}
                  disabled={disabled}
                  onHapus={() => handleHapusLokal(i)}
                />
              ))}

          {!penuh && (
            <button
              type="button"
              disabled={disabled || busy}
              onClick={() => inputRef.current?.click()}
              className="aspect-square rounded-lg border-2 border-dashed border-match-blue/40 text-match-blue hover:bg-match-blue/5 disabled:opacity-50 flex flex-col items-center justify-center gap-1 transition-colors"
            >
              <span className="text-xl leading-none">+</span>
              <span className="text-[10px] font-bold uppercase">
                {busy ? '...' : 'Tambah'}
              </span>
            </button>
          )}
        </div>
      )}

      <input
        ref={inputRef}
        type="file"
        accept=".jpg,.jpeg,.png"
        multiple
        onChange={handlePilih}
        className="hidden"
      />

      <p className="text-[11px] text-muted mt-1.5">Format JPG/PNG, maks 2MB per foto.</p>
      {error && <p className="text-[12px] font-bold text-whistle-red mt-1">{error}</p>}
      {msg && <p className="text-[12px] font-bold text-court-green mt-1">{msg}</p>}
    </div>
  )
}

function FotoTile({ url, utama, onHapus, onUtama, disabled }) {
  return (
    <div className="relative aspect-square rounded-lg overflow-hidden border border-black/10 group">
      <img src={url} alt="Foto lapangan" className="w-full h-full object-cover" />
      {utama && (
        <span className="absolute top-1 left-1 bg-court-green text-cream text-[9px] font-bold px-1.5 py-0.5 rounded uppercase">
          Utama
        </span>
      )}
      <div className="absolute inset-x-0 bottom-0 flex">
        {onUtama && !utama && (
          <button
            type="button"
            disabled={disabled}
            onClick={onUtama}
            title="Jadikan foto utama"
            className="flex-1 bg-ink/80 hover:bg-match-blue text-cream text-[9px] font-bold uppercase py-1 disabled:opacity-50 transition-colors"
          >
            Utama
          </button>
        )}
        <button
          type="button"
          disabled={disabled}
          onClick={onHapus}
          title="Hapus foto"
          className="flex-1 bg-whistle-red/90 hover:bg-whistle-red text-cream text-[9px] font-bold uppercase py-1 disabled:opacity-50 transition-colors"
        >
          Hapus
        </button>
      </div>
    </div>
  )
}

import { useState } from 'react'
import useLockBodyScroll from '../lib/useLockBodyScroll'
import { updateLapangan } from '../api/pemilik'
import { useVenues } from '../context/VenueContext'
import { namaJenis } from '../lib/format'
import FotoLapanganManager from './FotoLapanganManager'

export default function EditLapanganModal({ lapangan, onClose, onUpdated }) {
  useLockBodyScroll()
  const { categories } = useVenues()

  const [form, setForm] = useState({
    nama_lapangan: lapangan.nama_lapangan ?? '',
    jenis: lapangan.jenis ?? '',
    harga_per_jam: lapangan.harga_per_jam ?? '',
    alamat: lapangan.alamat ?? '',
    no_wa: lapangan.no_wa ?? '',
    kota: lapangan.kota ?? '',
  })
  const [submitting, setSubmitting] = useState(false)
  const [error, setError] = useState(null)

  function update(field) {
    return (e) => setForm((f) => ({ ...f, [field]: e.target.value }))
  }

  async function handleSubmit(e) {
    e.preventDefault()
    setSubmitting(true)
    setError(null)
    try {
      const res = await updateLapangan(lapangan.id, {
        ...form,
        harga_per_jam: Number(form.harga_per_jam),
      })
      onUpdated?.(res.data ?? res)
      onClose()
    } catch (err) {
      setError(err.message)
    } finally {
      setSubmitting(false)
    }
  }

  return (
    <div className="fixed inset-0 z-[100] bg-ink/60 flex justify-center px-4 py-8 overflow-y-auto overscroll-contain">
      <div className="my-auto bg-cream w-full max-w-md rounded-lg border-2 border-ink shadow-tactile p-6 relative">
        <button
          onClick={onClose}
          className="absolute top-3 right-3 text-muted hover:text-ink font-bold"
          aria-label="Tutup"
        >
          ✕
        </button>

        <h3 className="font-display text-2xl uppercase text-ink mb-1">Edit Lapangan</h3>
        <p className="text-xs text-muted mb-4">
          Perbarui informasi venue <strong className="text-ink">{lapangan.nama_lapangan}</strong>.
        </p>

        <form onSubmit={handleSubmit} className="space-y-3">
          <Field
            label="NAMA LAPANGAN"
            value={form.nama_lapangan}
            onChange={update('nama_lapangan')}
            required
          />

          <label className="block">
            <span className="block text-[11px] font-bold tracking-wider mb-1 text-ink">JENIS OLAHRAGA</span>
            <input
              list="edit-jenis-options"
              value={form.jenis}
              onChange={update('jenis')}
              placeholder="mis. futsal"
              required
              className="w-full bg-white border border-match-blue/20 rounded-lg px-2.5 py-2 text-sm font-semibold focus:border-match-blue focus:outline-none"
            />
            <datalist id="edit-jenis-options">
              {['futsal', 'badminton', 'basket', ...categories.map((c) => c.jenis)]
                .filter((v, i, arr) => arr.indexOf(v) === i)
                .map((j) => (
                  <option key={j} value={j}>
                    {namaJenis(j)}
                  </option>
                ))}
            </datalist>
          </label>

          <Field
            label="HARGA / JAM (RP)"
            type="number"
            min="0"
            value={form.harga_per_jam}
            onChange={update('harga_per_jam')}
            required
          />

          <Field
            label="ALAMAT LAPANGAN"
            value={form.alamat}
            onChange={update('alamat')}
            placeholder="mis. Jl. Merdeka No. 10, Bandung"
            required
          />

          <Field
            label="NOMOR WHATSAPP"
            type="tel"
            value={form.no_wa}
            onChange={update('no_wa')}
            placeholder="mis. 081234567890"
            required
          />

          <Field
            label="KOTA"
            value={form.kota}
            onChange={update('kota')}
            placeholder="mis. Bandung"
            required
          />

          <FotoLapanganManager lapanganId={lapangan.id} disabled={submitting} />

          {error && <p className="text-[12px] font-bold text-whistle-red">{error}</p>}

          <div className="flex items-center justify-end gap-2 pt-2">
            <button
              type="button"
              onClick={onClose}
              className="border border-black/20 text-muted hover:text-ink font-bold text-xs uppercase px-3 py-2 rounded-lg"
            >
              Batal
            </button>
            <button
              type="submit"
              disabled={submitting}
              className="bg-match-blue hover:bg-match-blue-dark disabled:opacity-60 text-cream font-bold text-xs uppercase px-4 py-2 rounded-lg shadow-tactile-sm transition-colors"
            >
              {submitting ? 'Menyimpan...' : 'Simpan Perubahan'}
            </button>
          </div>
        </form>
      </div>
    </div>
  )
}

function Field({ label, ...props }) {
  return (
    <label className="block">
      <span className="block text-[11px] font-bold tracking-wider mb-1 text-ink">{label}</span>
      <input
        {...props}
        className="w-full bg-white border border-match-blue/20 rounded-lg px-2.5 py-2 text-sm font-semibold focus:border-match-blue focus:outline-none"
      />
    </label>
  )
}

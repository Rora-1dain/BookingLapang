import { getToken } from '../api/client'

// ---------------------------------------------------------------------------
// Realtime chat lewat Pusher (Laravel Echo).
//
// Aktif hanya kalau VITE_PUSHER_APP_KEY & VITE_PUSHER_APP_CLUSTER di-set saat
// build. Kalau tidak, semua fungsi di sini no-op dan UI otomatis memakai
// polling (lihat ChatPage / useChatUnread).
//
// Channel private 'percakapan.{id}' diotorisasi backend lewat
// POST {API}/broadcasting/auth memakai Bearer token Sanctum.
// ---------------------------------------------------------------------------

const KEY = import.meta.env.VITE_PUSHER_APP_KEY
const CLUSTER = import.meta.env.VITE_PUSHER_APP_CLUSTER
const BASE_URL = import.meta.env.VITE_API_URL || '/api'

export const realtimeTersedia = Boolean(KEY && CLUSTER)

let echoPromise = null
let tokenDipakai = null
const refs = new Map() // nama channel -> jumlah pendengar

// Echo + pusher-js di-load lazy supaya tidak membebani bundle awal.
export function getEcho() {
  if (!realtimeTersedia) return Promise.resolve(null)
  const token = getToken()
  if (!token) return Promise.resolve(null)

  if (echoPromise && tokenDipakai === token) return echoPromise

  putus()
  tokenDipakai = token
  echoPromise = Promise.all([import('laravel-echo'), import('pusher-js')])
    .then(([{ default: Echo }, { default: Pusher }]) => {
      window.Pusher = Pusher
      return new Echo({
        broadcaster: 'pusher',
        key: KEY,
        cluster: CLUSTER,
        forceTLS: true,
        authEndpoint: `${BASE_URL}/broadcasting/auth`,
        auth: { headers: { Authorization: `Bearer ${token}`, Accept: 'application/json' } },
      })
    })
    .catch(() => null)
  return echoPromise
}

// Dipanggil saat logout / ganti akun.
export function putus() {
  const lama = echoPromise
  echoPromise = null
  tokenDipakai = null
  refs.clear()
  lama?.then((e) => e?.disconnect()).catch(() => {})
}

// Dengarkan satu percakapan. handlers: { onPesan(data), onDibaca(data) }.
// Mengembalikan fungsi untuk berhenti. Aman dipanggil dari beberapa komponen
// untuk channel yang sama (ref-count), channel baru di-leave saat pendengar terakhir lepas.
export function dengarPercakapan(id, { onPesan, onDibaca } = {}) {
  let batal = false
  let bersihkan = () => {}

  getEcho().then((echo) => {
    if (!echo || batal) return
    const nama = `percakapan.${id}`
    const channel = echo.private(nama)
    if (onPesan) channel.listen('.PesanDikirim', onPesan)
    if (onDibaca) channel.listen('.PesanDibaca', onDibaca)
    refs.set(nama, (refs.get(nama) || 0) + 1)

    bersihkan = () => {
      try {
        if (onPesan) channel.stopListening('.PesanDikirim', onPesan)
        if (onDibaca) channel.stopListening('.PesanDibaca', onDibaca)
        const sisa = (refs.get(nama) || 1) - 1
        if (sisa <= 0) {
          refs.delete(nama)
          echo.leave(nama)
        } else {
          refs.set(nama, sisa)
        }
      } catch {
        // koneksi sudah ditutup (mis. logout), abaikan
      }
    }
  })

  return () => {
    batal = true
    bersihkan()
  }
}

// Pantau status koneksi: 'connected' | 'connecting' | 'unavailable' | 'disconnected' | ...
// cb dipanggil langsung dengan status sekarang, lalu tiap berubah. Return: fungsi berhenti.
export function ikutiStatus(cb) {
  let batal = false
  let lepas = () => {}

  getEcho().then((echo) => {
    if (batal) return
    if (!echo) {
      cb('off')
      return
    }
    const koneksi = echo.connector?.pusher?.connection
    if (!koneksi) {
      cb('off')
      return
    }
    const handler = (state) => cb(state.current)
    cb(koneksi.state)
    koneksi.bind('state_change', handler)
    lepas = () => koneksi.unbind('state_change', handler)
  })

  return () => {
    batal = true
    lepas()
  }
}
import { useEffect, useRef, useState } from 'react'
import { fetchPercakapan } from '../api/chat'
import { dengarPercakapan } from './realtime'

// Total pesan belum dibaca untuk badge di Navbar.
// - Realtime: berlangganan channel tiap percakapan, refresh saat ada pesan/dibaca.
// - Fallback: polling 30 dtk (berhenti saat tab tidak aktif) + sinyal 'chat-updated'.
export default function useChatUnread(enabled) {
  const [total, setTotal] = useState(0)
  const [ids, setIds] = useState([])
  const cekRef = useRef(() => {})

  useEffect(() => {
    if (!enabled) {
      setTotal(0)
      setIds([])
      return undefined
    }
    let berhenti = false

    async function cek() {
      if (document.hidden) return
      try {
        const list = await fetchPercakapan()
        if (berhenti) return
        setTotal(list.reduce((n, p) => n + (p.belum_dibaca || 0), 0))
        setIds((lama) => {
          const baru = list.map((p) => p.id)
          return lama.join(',') === baru.join(',') ? lama : baru
        })
      } catch {
        // diam saja: badge bukan fitur kritikal
      }
    }
    cekRef.current = cek

    cek()
    const timer = setInterval(cek, 30000)
    window.addEventListener('chat-updated', cek)
    return () => {
      berhenti = true
      clearInterval(timer)
      window.removeEventListener('chat-updated', cek)
    }
  }, [enabled])

  const idsKey = ids.join(',')
  useEffect(() => {
    if (!enabled || ids.length === 0) return undefined
    const handler = () => cekRef.current()
    const lepasSemua = ids.map((id) => dengarPercakapan(id, { onPesan: handler, onDibaca: handler }))
    return () => lepasSemua.forEach((lepas) => lepas())
    // eslint-disable-next-line react-hooks/exhaustive-deps
  }, [enabled, idsKey])

  return total
}
import { useEffect } from 'react'

// Kunci scroll halaman di belakang modal supaya cuma ada satu scrollbar
// (milik modal) dan roda mouse tidak menggeser halaman utama.
export default function useLockBodyScroll() {
  useEffect(() => {
    const sebelumnya = document.body.style.overflow
    document.body.style.overflow = 'hidden'
    return () => {
      document.body.style.overflow = sebelumnya
    }
  }, [])
}
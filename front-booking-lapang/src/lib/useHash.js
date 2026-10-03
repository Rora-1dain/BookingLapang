import { useEffect, useState } from 'react'

// Routing ringan berbasis hash (tanpa react-router, jadi tidak butuh rewrite di Vercel).
export default function useHash() {
  const [hash, setHash] = useState(window.location.hash)
  useEffect(() => {
    const onChange = () => setHash(window.location.hash)
    window.addEventListener('hashchange', onChange)
    return () => window.removeEventListener('hashchange', onChange)
  }, [])
  return hash
}
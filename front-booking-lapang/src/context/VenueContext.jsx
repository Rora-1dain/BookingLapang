import { createContext, useCallback, useContext, useEffect, useMemo, useState } from 'react'
import { fetchLapangan } from '../api/lapangan'

const VenueContext = createContext(null)

export function VenueProvider({ children }) {
  const [venues, setVenues] = useState([])
  const [meta, setMeta] = useState(null)
  const [loading, setLoading] = useState(true)
  const [error, setError] = useState(null)
  const [filters, setFilters] = useState({ jenis: '', kota: '', kata_kunci: '' })

  const load = useCallback(async (kriteria) => {
    setLoading(true)
    setError(null)
    try {
      const { data, meta } = await fetchLapangan(kriteria)
      setVenues(data)
      setMeta(meta)
    } catch (err) {
      setError(err.message || 'Gagal memuat daftar lapangan.')
      setVenues([])
    } finally {
      setLoading(false)
    }
  }, [])

  // muat ulang setiap kali filter berubah
  useEffect(() => {
    load(filters)
  }, [filters, load])

  // kategori olahraga diturunkan dari data asli (bukan lagi angka statis),
  // karena backend tidak punya endpoint /api/categories tersendiri
  const categories = useMemo(() => {
    const counts = new Map()
    venues.forEach((v) => {
      const key = (v.jenis || '-').toLowerCase()
      counts.set(key, (counts.get(key) || 0) + 1)
    })
    return Array.from(counts.entries()).map(([jenis, count]) => ({ jenis, count }))
  }, [venues])

  const featured = useMemo(() => {
    if (venues.length === 0) return null
    return [...venues].sort((a, b) => (b.rating || 0) - (a.rating || 0))[0]
  }, [venues])

  const value = {
    venues,
    meta,
    loading,
    error,
    filters,
    setFilters,
    categories,
    featured,
    reload: () => load(filters),
  }

  return <VenueContext.Provider value={value}>{children}</VenueContext.Provider>
}

export function useVenues() {
  const ctx = useContext(VenueContext)
  if (!ctx) throw new Error('useVenues harus dipakai di dalam <VenueProvider>')
  return ctx
}

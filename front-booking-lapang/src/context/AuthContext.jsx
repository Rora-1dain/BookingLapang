import { createContext, useCallback, useContext, useEffect, useState } from 'react'
import * as authApi from '../api/auth'
import { getToken, getUser, setToken, setUser as simpanUser } from '../api/client'
import { putus } from '../lib/realtime'

const AuthContext = createContext(null)

export function AuthProvider({ children }) {
  // Hydrate langsung dari localStorage supaya saat hard refresh UI tidak
  // "kosong" sekejap dan tidak sempat dianggap logout.
  const [user, setUser] = useState(() => getUser())
  const [checking, setChecking] = useState(true)

  useEffect(() => {
    const token = getToken()
    if (!token) {
      // Tidak ada token: pastikan sisa data user lama dibersihkan.
      setUser(null)
      simpanUser(null)
      setChecking(false)
      return
    }
    authApi
      .me()
      .then((u) => {
        setUser(u)
        simpanUser(u)
      })
      .catch((err) => {
        // HANYA hapus token kalau server benar-benar menolak (401/403) —
        // artinya token memang invalid. Kalau error jaringan/CORS (status 0),
        // server 5xx, atau balasan non-JSON, JANGAN logout: pertahankan token
        // & user yang sudah di-hydrate, supaya hard refresh tidak memaksa
        // login ulang.
        if (err?.status === 401 || err?.status === 403) {
          setToken(null)
          setUser(null)
          simpanUser(null)
        }
      })
      .finally(() => setChecking(false))
  }, [])

  const login = useCallback(async (email, password) => {
    const res = await authApi.login(email, password)
    setToken(res.token)
    setUser(res.user)
    simpanUser(res.user)
    return res.user
  }, [])

  const register = useCallback(async (payload) => {
    const res = await authApi.register(payload)
    setToken(res.token)
    setUser(res.user)
    simpanUser(res.user)
    return res.user
  }, [])

  const logout = useCallback(async () => {
    try {
      await authApi.logout()
    } catch {
      // token sudah invalid di server, tetap bersihkan sisi client
    }
    setToken(null)
    setUser(null)
    simpanUser(null)
    putus() // tutup koneksi realtime milik akun sebelumnya
  }, [])

  return (
    <AuthContext.Provider value={{ user, checking, login, register, logout }}>
      {children}
    </AuthContext.Provider>
  )
}

export function useAuth() {
  const ctx = useContext(AuthContext)
  if (!ctx) throw new Error('useAuth harus dipakai di dalam <AuthProvider>')
  return ctx
}

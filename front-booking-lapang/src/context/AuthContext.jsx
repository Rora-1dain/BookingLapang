import { createContext, useCallback, useContext, useEffect, useState } from 'react'
import * as authApi from '../api/auth'
import { getToken, setToken } from '../api/client'
import { putus } from '../lib/realtime'

const AuthContext = createContext(null)

export function AuthProvider({ children }) {
  const [user, setUser] = useState(null)
  const [checking, setChecking] = useState(true)

  useEffect(() => {
    const token = getToken()
    if (!token) {
      setChecking(false)
      return
    }
    authApi
      .me()
      .then(setUser)
      .catch(() => setToken(null))
      .finally(() => setChecking(false))
  }, [])

  const login = useCallback(async (email, password) => {
    const res = await authApi.login(email, password)
    setToken(res.token)
    setUser(res.user)
    return res.user
  }, [])

  const register = useCallback(async (payload) => {
    const res = await authApi.register(payload)
    setToken(res.token)
    setUser(res.user)
    return res.user
  }, [])

  const logout = useCallback(async () => {
    try {
      await authApi.logout()
    } catch {
      // token sudah invalid di server, tetap bersihkan sisi client
    }
    setToken(null)
    putus() // tutup koneksi realtime milik akun sebelumnya
    setUser(null)
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
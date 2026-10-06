import { apiFetch } from './client'

export async function login(email, password) {
  const res = await apiFetch('/login', { method: 'POST', body: { email, password } })
  const user = res?.user?.data ?? res?.user
  return { ...res, user }
}

// role: 'user' (pemesan lapangan) | 'pemilik_lapangan'
export async function register({ name, email, password, password_confirmation, kode_referral, role }) {
  const res = await apiFetch('/register', {
    method: 'POST',
    body: { name, email, password, password_confirmation, kode_referral, role },
  })
  const user = res?.user?.data ?? res?.user
  return { ...res, user }
}

export async function me() {
  const res = await apiFetch('/me', { auth: true })
  return res?.data ?? res
}

export function logout() {
  return apiFetch('/logout', { method: 'POST', auth: true })
}

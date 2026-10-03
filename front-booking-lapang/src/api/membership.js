import { apiFetch } from './client'

// GET /api/membership/paket (publik)
export async function fetchPaket() {
  return apiFetch('/membership/paket')
}

// GET /api/membership — langganan aktif milik user, atau data: null
export async function fetchMembershipSaya() {
  return apiFetch('/membership', { auth: true })
}

// POST /api/membership/berlangganan
export async function berlangganan(membershipPaketId) {
  return apiFetch('/membership/berlangganan', {
    method: 'POST',
    auth: true,
    body: { membership_paket_id: membershipPaketId },
  })
}
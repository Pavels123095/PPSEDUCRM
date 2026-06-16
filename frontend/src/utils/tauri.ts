import { setAuthToken } from '../api/client'

const TOKEN_KEY = 'auth_token'

export async function saveToken(token: string): Promise<void> {
  if (window.__TAURI__) {
    const { load } = await import('@tauri-apps/plugin-store')
    const store = await load('auth.json', { autoSave: true, defaults: {} })
    await store.set(TOKEN_KEY, token)
    await store.save()
  }
  setAuthToken(token)
}

export async function loadToken(): Promise<string | null> {
  if (window.__TAURI__) {
    try {
      const { load } = await import('@tauri-apps/plugin-store')
      const store = await load('auth.json', { autoSave: true, defaults: {} })
      const token = await store.get<string>(TOKEN_KEY)
      if (token) {
        setAuthToken(token)
        return token
      }
    } catch {
      // fallback to localStorage
    }
  }
  return localStorage.getItem(TOKEN_KEY)
}

export async function clearToken(): Promise<void> {
  if (window.__TAURI__) {
    try {
      const { load } = await import('@tauri-apps/plugin-store')
      const store = await load('auth.json', { autoSave: true, defaults: {} })
      await store.delete(TOKEN_KEY)
      await store.save()
    } catch {
      // ignore
    }
  }
  setAuthToken(null)
}

declare global {
  interface Window {
    __TAURI__?: unknown
  }
}

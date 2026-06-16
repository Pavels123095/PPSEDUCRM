import { createContext, useContext, useState, useEffect, type ReactNode } from 'react'
import { api } from '../api/client'
import { loadToken, saveToken, clearToken } from '../utils/tauri'
import type { User, UserRole } from '../types'

interface AuthContextType {
  user: User | null
  loading: boolean
  login: (email: string, password: string) => Promise<void>
  logout: () => Promise<void>
  hasRole: (role: UserRole) => boolean
}

const AuthContext = createContext<AuthContextType | null>(null)

export function AuthProvider({ children }: { children: ReactNode }) {
  const [user, setUser] = useState<User | null>(null)
  const [loading, setLoading] = useState(true)

  useEffect(() => {
    loadToken().then((token) => {
      if (token) {
        api.get('/auth/me')
          .then((res) => setUser(res.data))
          .catch(() => clearToken())
          .finally(() => setLoading(false))
      } else {
        setLoading(false)
      }
    })
  }, [])

  const login = async (email: string, password: string) => {
    const res = await api.post('/auth/login', { email, password })
    await saveToken(res.data.token)
    setUser(res.data.user)
  }

  const logout = async () => {
    try {
      await api.post('/auth/logout')
    } finally {
      await clearToken()
      setUser(null)
    }
  }

  const hasRole = (role: UserRole) => user?.roles?.includes(role) ?? false

  return (
    <AuthContext.Provider value={{ user, loading, login, logout, hasRole }}>
      {children}
    </AuthContext.Provider>
  )
}

export function useAuth() {
  const ctx = useContext(AuthContext)
  if (!ctx) throw new Error('useAuth must be used within AuthProvider')
  return ctx
}

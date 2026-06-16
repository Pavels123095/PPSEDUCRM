import { Link, useLocation } from 'react-router-dom'
import { useAuth } from '../context/AuthContext'
import ru from '../i18n/ru.json'

interface NavItem {
  path: string
  label: string
}

function getNavItems(roles: string[]): NavItem[] {
  if (roles.includes('admin')) {
    return [
      { path: '/admin/integrations', label: ru.nav.integrations },
      { path: '/admin/references', label: ru.nav.references },
    ]
  }
  if (roles.includes('manager')) {
    return [
      { path: '/manager', label: ru.nav.dashboard },
      { path: '/manager/applicants', label: ru.nav.applicants },
    ]
  }
  if (roles.includes('teacher')) {
    return [
      { path: '/teacher/schedule', label: ru.nav.schedule },
      { path: '/teacher/hours', label: ru.nav.hours },
      { path: '/teacher/classrooms', label: ru.nav.classrooms },
    ]
  }
  if (roles.includes('student')) {
    return [
      { path: '/student', label: ru.nav.schedule },
      { path: '/student/profile', label: ru.nav.profile },
      { path: '/student/notifications', label: ru.nav.notifications },
    ]
  }
  return []
}

export function ResponsiveLayout({ children }: { children: React.ReactNode }) {
  const { user, logout } = useAuth()
  const location = useLocation()
  const navItems = user ? getNavItems(user.roles) : []

  return (
    <div className="min-h-screen flex flex-col md:flex-row">
      <aside className="hidden md:flex md:w-64 md:flex-col bg-slate-800 text-white">
        <div className="p-4 border-b border-slate-700">
          <h1 className="font-bold text-lg">{ru.app.title}</h1>
          <p className="text-xs text-slate-400">{user?.name}</p>
        </div>
        <nav className="flex-1 p-4 space-y-1">
          {navItems.map((item) => (
            <Link
              key={item.path}
              to={item.path}
              className={`block px-3 py-2 rounded-lg text-sm ${
                location.pathname.startsWith(item.path)
                  ? 'bg-slate-700'
                  : 'hover:bg-slate-700'
              }`}
            >
              {item.label}
            </Link>
          ))}
        </nav>
        <button
          onClick={() => logout()}
          className="m-4 px-3 py-2 text-sm text-slate-300 hover:text-white text-left"
        >
          {ru.auth.logout}
        </button>
      </aside>

      <main className="flex-1 flex flex-col pb-16 md:pb-0">
        <header className="md:hidden bg-slate-800 text-white px-4 py-3 flex justify-between items-center">
          <span className="font-bold">{ru.app.title}</span>
          <button onClick={() => logout()} className="text-sm text-slate-300">
            {ru.auth.logout}
          </button>
        </header>
        <div className="flex-1 p-4 md:p-6 max-w-6xl mx-auto w-full">{children}</div>
      </main>

      <nav className="md:hidden fixed bottom-0 left-0 right-0 bg-white border-t border-slate-200 flex justify-around py-2">
        {navItems.map((item) => (
          <Link
            key={item.path}
            to={item.path}
            className={`text-xs px-2 py-1 text-center ${
              location.pathname.startsWith(item.path)
                ? 'text-blue-600 font-medium'
                : 'text-slate-500'
            }`}
          >
            {item.label}
          </Link>
        ))}
      </nav>
    </div>
  )
}

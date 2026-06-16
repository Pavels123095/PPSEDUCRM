import { useQuery } from '@tanstack/react-query'
import { api } from '../../api/client'
import { Card } from '../../components/Card'
import ru from '../../i18n/ru.json'
import type { Notification } from '../../types'

export function StudentNotifications() {
  const { data, isLoading } = useQuery({
    queryKey: ['student-notifications'],
    queryFn: async () => {
      const res = await api.get<{ data: Notification[] } | Notification[]>('/student/notifications')
      const payload = res.data
      return Array.isArray(payload) ? payload : payload.data ?? []
    },
  })

  if (isLoading) return <p>Загрузка...</p>

  return (
    <div className="space-y-4">
      <h1 className="text-2xl font-bold">{ru.nav.notifications}</h1>
      {(data ?? []).map((n) => (
        <Card key={n.id}>
          <p className="font-medium">{n.title}</p>
          <p className="text-sm text-slate-600">{n.body}</p>
          <p className="text-xs text-slate-400 mt-1">
            {new Date(n.created_at).toLocaleString('ru-RU')}
          </p>
        </Card>
      ))}
      {(data ?? []).length === 0 && <p className="text-slate-500">{ru.common.noData}</p>}
    </div>
  )
}

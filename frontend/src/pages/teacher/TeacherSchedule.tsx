import { useQuery } from '@tanstack/react-query'
import { api } from '../../api/client'
import { Card } from '../../components/Card'
import ru from '../../i18n/ru.json'
import type { ScheduleSlot } from '../../types'

function formatDateTime(iso: string) {
  return new Date(iso).toLocaleString('ru-RU', {
    day: '2-digit',
    month: '2-digit',
    hour: '2-digit',
    minute: '2-digit',
  })
}

export function TeacherSchedule() {
  const { data, isLoading } = useQuery({
    queryKey: ['schedule-slots'],
    queryFn: async () => {
      const res = await api.get<{ data: ScheduleSlot[] }>('/schedule-slots')
      return Array.isArray(res.data) ? res.data : res.data.data ?? []
    },
  })

  if (isLoading) return <p>Загрузка...</p>

  const slots = data ?? []

  return (
    <div className="space-y-4">
      <h1 className="text-2xl font-bold">Расписание</h1>
      <div className="grid gap-3 md:grid-cols-2">
        {slots.map((slot) => (
          <Card key={slot.id}>
            <p className="font-medium">{slot.title}</p>
            <p className="text-sm text-slate-500">
              {(ru.slotType as Record<string, string>)[slot.type]}
            </p>
            <p className="text-sm mt-1">
              {formatDateTime(slot.starts_at)} — {formatDateTime(slot.ends_at)}
            </p>
            <p className="text-sm">
              Аудитория: {slot.classroom?.building}-{slot.classroom?.number}
            </p>
          </Card>
        ))}
      </div>
      {slots.length === 0 && <p className="text-slate-500">{ru.common.noData}</p>}
    </div>
  )
}

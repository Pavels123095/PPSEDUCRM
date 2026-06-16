import { useQuery } from '@tanstack/react-query'
import { api } from '../../api/client'
import { Card } from '../../components/Card'
import ru from '../../i18n/ru.json'
import type { ScheduleSlot } from '../../types'

function formatTime(iso: string) {
  return new Date(iso).toLocaleString('ru-RU', {
    weekday: 'short',
    day: '2-digit',
    month: '2-digit',
    hour: '2-digit',
    minute: '2-digit',
  })
}

export function StudentSchedule() {
  const { data, isLoading } = useQuery({
    queryKey: ['student-schedule'],
    queryFn: async () => {
      const res = await api.get<{ schedule: ScheduleSlot[] }>('/student/schedule')
      return res.data.schedule ?? []
    },
  })

  if (isLoading) return <p>Загрузка...</p>

  const slots = Array.isArray(data) ? data : []

  return (
    <div className="space-y-4">
      <h1 className="text-2xl font-bold">Моё расписание</h1>
      {slots.map((slot) => (
        <Card key={slot.id}>
          <p className="font-medium">{slot.title}</p>
          <p className="text-sm text-blue-600">{formatTime(slot.starts_at)}</p>
          <p className="text-sm">
            {(ru.slotType as Record<string, string>)[slot.type]} · Ауд. {slot.classroom?.building}-{slot.classroom?.number}
          </p>
        </Card>
      ))}
      {slots.length === 0 && <p className="text-slate-500">{ru.common.noData}</p>}
    </div>
  )
}

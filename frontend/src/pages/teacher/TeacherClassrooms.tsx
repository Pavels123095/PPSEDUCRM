import { useQuery } from '@tanstack/react-query'
import { api } from '../../api/client'
import { Card } from '../../components/Card'
import type { Classroom } from '../../types'

export function TeacherClassrooms() {
  const { data, isLoading } = useQuery({
    queryKey: ['classrooms'],
    queryFn: async () => {
      const res = await api.get<Classroom[]>('/classrooms')
      return Array.isArray(res.data) ? res.data : []
    },
  })

  if (isLoading) return <p>Загрузка...</p>

  return (
    <div className="space-y-4">
      <h1 className="text-2xl font-bold">Аудитории</h1>
      <div className="grid gap-3 md:grid-cols-3">
        {(data ?? []).map((c) => (
          <Card key={c.id}>
            <p className="font-medium">{c.building} — {c.number}</p>
            <p className="text-sm text-slate-500">Вместимость: {c.capacity}</p>
            {c.equipment && <p className="text-sm mt-1">{c.equipment}</p>}
          </Card>
        ))}
      </div>
    </div>
  )
}

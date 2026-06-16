import { useState } from 'react'
import { useQuery, useMutation, useQueryClient } from '@tanstack/react-query'
import { api } from '../../api/client'
import { Card } from '../../components/Card'
import { Button } from '../../components/Button'
import ru from '../../i18n/ru.json'
import { useAuth } from '../../context/AuthContext'
import type { WorkSession } from '../../types'

export function TeacherHours() {
  const { user } = useAuth()
  const queryClient = useQueryClient()
  const teacherId = user?.teacher?.id
  const [form, setForm] = useState({
    activity_type: 'lecture' as const,
    hours: 2,
    session_date: new Date().toISOString().split('T')[0],
    notes: '',
  })

  const { data, isLoading } = useQuery({
    queryKey: ['work-sessions'],
    queryFn: async () => {
      const res = await api.get<{ data: WorkSession[] }>('/work-sessions')
      return Array.isArray(res.data) ? res.data : res.data.data ?? []
    },
  })

  const mutation = useMutation({
    mutationFn: () => api.post('/work-sessions', { ...form, teacher_id: teacherId }),
    onSuccess: () => {
      queryClient.invalidateQueries({ queryKey: ['work-sessions'] })
      setForm((f) => ({ ...f, notes: '' }))
    },
  })

  if (isLoading) return <p>Загрузка...</p>

  return (
    <div className="space-y-4">
      <h1 className="text-2xl font-bold">Учёт часов</h1>
      <Card title="Добавить запись">
        <div className="grid gap-3 md:grid-cols-2">
          <select
            value={form.activity_type}
            onChange={(e) => setForm({ ...form, activity_type: e.target.value as typeof form.activity_type })}
            className="border rounded-lg px-3 py-2"
          >
            {Object.entries(ru.slotType).map(([k, v]) => (
              <option key={k} value={k}>{v}</option>
            ))}
          </select>
          <input
            type="number"
            step="0.5"
            value={form.hours}
            onChange={(e) => setForm({ ...form, hours: parseFloat(e.target.value) })}
            className="border rounded-lg px-3 py-2"
          />
          <input
            type="date"
            value={form.session_date}
            onChange={(e) => setForm({ ...form, session_date: e.target.value })}
            className="border rounded-lg px-3 py-2"
          />
          <input
            value={form.notes}
            onChange={(e) => setForm({ ...form, notes: e.target.value })}
            placeholder="Примечание"
            className="border rounded-lg px-3 py-2"
          />
        </div>
        <Button className="mt-3" onClick={() => mutation.mutate()} disabled={mutation.isPending}>
          {ru.common.save}
        </Button>
      </Card>
      <Card title="Записи">
        {(data ?? []).map((w) => (
          <div key={w.id} className="py-2 border-b text-sm">
            {(w as WorkSession & { session_date?: string }).session_date || (w as { date?: string }).date} — {(ru.slotType as Record<string, string>)[w.activity_type]}: {w.hours} ч.
          </div>
        ))}
      </Card>
    </div>
  )
}

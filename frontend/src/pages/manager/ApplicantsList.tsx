import { useQuery } from '@tanstack/react-query'
import { Link } from 'react-router-dom'
import { api } from '../../api/client'
import { Card } from '../../components/Card'
import { StatusBadge } from '../../components/StatusBadge'
import { Button } from '../../components/Button'
import type { Applicant } from '../../types'

export function ApplicantsList() {
  const { data, isLoading, refetch } = useQuery({
    queryKey: ['applicants'],
    queryFn: async () => {
      const res = await api.get<{ data: Applicant[] } | Applicant[]>('/applicants')
      const payload = res.data
      return Array.isArray(payload) ? payload : payload.data ?? []
    },
  })

  const applicants = Array.isArray(data) ? data : []

  if (isLoading) return <p>Загрузка...</p>

  return (
    <div className="space-y-4">
      <div className="flex justify-between items-center">
        <h1 className="text-2xl font-bold">Абитуриенты</h1>
        <Button onClick={() => refetch()}>Обновить</Button>
      </div>
      <div className="hidden md:block">
        <table className="w-full bg-white rounded-xl border border-slate-200">
          <thead className="bg-slate-50">
            <tr>
              <th className="text-left p-3 text-sm">ФИО</th>
              <th className="text-left p-3 text-sm">Телефон</th>
              <th className="text-left p-3 text-sm">Статус</th>
            </tr>
          </thead>
          <tbody>
            {applicants.map((a) => (
              <tr key={a.id} className="border-t border-slate-100">
                <td className="p-3">
                  <Link to={`/manager/applicants/${a.id}`} className="text-blue-600 hover:underline">
                    {a.last_name} {a.first_name} {a.middle_name || ''}
                  </Link>
                </td>
                <td className="p-3 text-sm">{a.phone || '—'}</td>
                <td className="p-3"><StatusBadge status={a.status} /></td>
              </tr>
            ))}
          </tbody>
        </table>
      </div>
      <div className="md:hidden space-y-3">
        {applicants.map((a) => (
          <Card key={a.id}>
            <Link to={`/manager/applicants/${a.id}`}>
              <p className="font-medium">{a.last_name} {a.first_name}</p>
              <p className="text-sm text-slate-500">{a.phone}</p>
              <div className="mt-2"><StatusBadge status={a.status} /></div>
            </Link>
          </Card>
        ))}
      </div>
    </div>
  )
}

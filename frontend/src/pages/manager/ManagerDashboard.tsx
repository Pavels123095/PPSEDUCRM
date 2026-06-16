import { useQuery } from '@tanstack/react-query'
import { Link } from 'react-router-dom'
import { api } from '../../api/client'
import { Card } from '../../components/Card'
import { StatusBadge } from '../../components/StatusBadge'
import type { DashboardStats } from '../../types'

export function ManagerDashboard() {
  const { data, isLoading } = useQuery({
    queryKey: ['manager-dashboard'],
    queryFn: async () => {
      const res = await api.get<DashboardStats>('/managers/dashboard')
      return res.data
    },
  })

  if (isLoading) return <p>Загрузка...</p>

  return (
    <div className="space-y-6">
      <h1 className="text-2xl font-bold">Панель менеджера</h1>
      <div className="grid grid-cols-2 md:grid-cols-3 gap-4">
        {data?.funnel &&
          Object.entries(data.funnel).map(([status, count]) => (
            <Card key={status}>
              <StatusBadge status={status} />
              <p className="text-3xl font-bold mt-2">{count}</p>
            </Card>
          ))}
        <Card>
          <p className="text-sm text-slate-500">Всего</p>
          <p className="text-3xl font-bold">{data?.total ?? 0}</p>
        </Card>
      </div>
      <Card title="Недавние абитуриенты">
        <div className="space-y-2">
          {data?.recent_applicants?.map((a) => (
            <Link
              key={a.id}
              to={`/manager/applicants/${a.id}`}
              className="flex justify-between items-center p-2 hover:bg-slate-50 rounded-lg"
            >
              <span>
                {a.last_name} {a.first_name}
              </span>
              <StatusBadge status={a.status} />
            </Link>
          ))}
        </div>
      </Card>
    </div>
  )
}

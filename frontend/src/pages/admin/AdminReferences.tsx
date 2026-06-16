import { useQuery } from '@tanstack/react-query'
import { api } from '../../api/client'
import { Card } from '../../components/Card'

export function AdminReferences() {
  const groups = useQuery({
    queryKey: ['groups'],
    queryFn: async () => (await api.get('/groups')).data,
  })
  const teachers = useQuery({
    queryKey: ['teachers-list'],
    queryFn: async () => (await api.get('/teachers-list')).data,
  })

  return (
    <div className="space-y-4">
      <h1 className="text-2xl font-bold">Справочники</h1>
      <Card title="Группы">
        <pre className="text-xs overflow-auto">{JSON.stringify(groups.data, null, 2)}</pre>
      </Card>
      <Card title="Педагоги">
        <pre className="text-xs overflow-auto">{JSON.stringify(teachers.data, null, 2)}</pre>
      </Card>
    </div>
  )
}

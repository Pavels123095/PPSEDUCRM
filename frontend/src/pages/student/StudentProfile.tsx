import { useQuery } from '@tanstack/react-query'
import { api } from '../../api/client'
import { Card } from '../../components/Card'
interface ProfileResponse {
  user: { id: number; name: string; email: string }
  student: { course: number }
  study_group?: { name: string }
}

export function StudentProfile() {
  const { data, isLoading } = useQuery({
    queryKey: ['student-profile'],
    queryFn: async () => {
      const res = await api.get<ProfileResponse>('/student/profile')
      return res.data
    },
  })

  if (isLoading) return <p>Загрузка...</p>
  if (!data) return null

  return (
    <div className="space-y-4">
      <h1 className="text-2xl font-bold">Профиль</h1>
      <Card>
        <p><strong>ФИО:</strong> {data.user.name}</p>
        <p><strong>Email:</strong> {data.user.email}</p>
        <p><strong>Курс:</strong> {data.student.course}</p>
        <p><strong>Группа:</strong> {data.study_group?.name || '—'}</p>
      </Card>
    </div>
  )
}

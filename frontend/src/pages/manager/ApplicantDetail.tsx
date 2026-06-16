import { useState } from 'react'
import { useParams } from 'react-router-dom'
import { useQuery, useMutation, useQueryClient } from '@tanstack/react-query'
import { api } from '../../api/client'
import { Card } from '../../components/Card'
import { StatusBadge } from '../../components/StatusBadge'
import { Button } from '../../components/Button'
import type { Applicant } from '../../types'
import ru from '../../i18n/ru.json'

const STATUSES = ['new', 'contacted', 'contract_draft', 'contract_signed', 'enrolled', 'rejected']

export function ApplicantDetail() {
  const { id } = useParams<{ id: string }>()
  const queryClient = useQueryClient()
  const [contractNumber, setContractNumber] = useState('')

  const { data: applicant, isLoading } = useQuery({
    queryKey: ['applicant', id],
    queryFn: async () => {
      const res = await api.get<Applicant>(`/applicants/${id}`)
      return res.data
    },
    enabled: !!id,
  })

  const statusMutation = useMutation({
    mutationFn: (status: string) => api.patch(`/applicants/${id}/status`, { status }),
    onSuccess: () => queryClient.invalidateQueries({ queryKey: ['applicant', id] }),
  })

  const contractMutation = useMutation({
    mutationFn: () => api.post(`/applicants/${id}/contracts`, { number: contractNumber }),
    onSuccess: () => {
      queryClient.invalidateQueries({ queryKey: ['applicant', id] })
      setContractNumber('')
    },
  })

  const signMutation = useMutation({
    mutationFn: (contractId: string) =>
      api.post(`/contracts/${contractId}/sign`, { signed_at: new Date().toISOString().split('T')[0] }),
    onSuccess: () => queryClient.invalidateQueries({ queryKey: ['applicant', id] }),
  })

  if (isLoading || !applicant) return <p>Загрузка...</p>

  return (
    <div className="space-y-4">
      <h1 className="text-2xl font-bold">
        {applicant.last_name} {applicant.first_name} {applicant.middle_name || ''}
      </h1>
      <StatusBadge status={applicant.status} />

      <Card title="Контакты">
        <p>Email: {applicant.email || '—'}</p>
        <p>Телефон: {applicant.phone || '—'}</p>
        <p>СНИЛС: {applicant.snils || '—'}</p>
      </Card>

      <Card title="Сменить статус">
        <div className="flex flex-wrap gap-2">
          {STATUSES.map((s) => (
            <Button
              key={s}
              variant={applicant.status === s ? 'primary' : 'secondary'}
              onClick={() => statusMutation.mutate(s)}
              disabled={statusMutation.isPending}
            >
              {(ru.status as Record<string, string>)[s]}
            </Button>
          ))}
        </div>
      </Card>

      <Card title="Договоры">
        {applicant.contracts?.map((c) => (
          <div key={c.id} className="flex justify-between items-center py-2 border-b">
            <span>№ {c.number} — {c.status}</span>
            {c.status !== 'signed' && (
              <Button onClick={() => signMutation.mutate(c.id)} disabled={signMutation.isPending}>
                Подписать
              </Button>
            )}
          </div>
        ))}
        <div className="flex gap-2 mt-4">
          <input
            value={contractNumber}
            onChange={(e) => setContractNumber(e.target.value)}
            placeholder="Номер договора"
            className="border rounded-lg px-3 py-2 flex-1"
          />
          <Button onClick={() => contractMutation.mutate()} disabled={!contractNumber}>
            Создать
          </Button>
        </div>
      </Card>
    </div>
  )
}

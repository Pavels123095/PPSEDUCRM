import ru from '../i18n/ru.json'

export function StatusBadge({ status }: { status: string }) {
  const label = (ru.status as Record<string, string>)[status] || status
  const colors: Record<string, string> = {
    new: 'bg-blue-100 text-blue-800',
    contacted: 'bg-yellow-100 text-yellow-800',
    contract_draft: 'bg-orange-100 text-orange-800',
    contract_signed: 'bg-green-100 text-green-800',
    enrolled: 'bg-emerald-100 text-emerald-800',
    rejected: 'bg-red-100 text-red-800',
  }
  return (
    <span className={`inline-block px-2 py-0.5 rounded-full text-xs font-medium ${colors[status] || 'bg-slate-100 text-slate-800'}`}>
      {label}
    </span>
  )
}

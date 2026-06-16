import { Card } from '../../components/Card'

export function AdminIntegrations() {
  return (
    <div className="space-y-4">
      <h1 className="text-2xl font-bold">Интеграция 1С</h1>
      <Card title="API endpoints">
        <ul className="text-sm space-y-2 font-mono">
          <li>POST /api/integrations/1c/webhook</li>
          <li>GET /api/integrations/1c/export/&#123;entity&#125;</li>
          <li>POST /api/integrations/1c/import</li>
        </ul>
      </Card>
      <Card title="Сущности для синхронизации">
        <p className="text-sm">applicants, students, contracts, teachers</p>
        <p className="text-sm text-slate-500 mt-2">
          Поля: external_id, sync_status, last_synced_at
        </p>
      </Card>
    </div>
  )
}

export type UserRole = 'admin' | 'manager' | 'teacher' | 'student'

export interface User {
  id: number
  name: string
  email: string
  roles: UserRole[]
  teacher?: { id: number }
  manager?: { id: number }
  student?: { id: number }
}

export interface Applicant {
  id: string
  first_name: string
  last_name: string
  middle_name?: string
  email?: string
  phone?: string
  snils?: string
  passport_series?: string
  passport_number?: string
  status: string
  manager_id?: number
  manager?: { id: number; user?: { name: string } }
  contracts?: Contract[]
  created_at: string
}

export interface Contract {
  id: string
  number: string
  status: string
  signed_at?: string
  file_path?: string
  applicant_id: string
}

export interface Classroom {
  id: number
  number: string
  building: string
  capacity: number
  equipment?: string
}

export interface ScheduleSlot {
  id: string
  title: string
  type: 'lecture' | 'lab' | 'consultation'
  starts_at: string
  ends_at: string
  classroom_id: number
  teacher_id: number
  study_group_id?: number
  classroom?: Classroom
  teacher?: { id: number; user?: { name: string } }
  study_group?: { id: number; name: string }
}

export interface WorkSession {
  id: string
  activity_type: 'lecture' | 'lab' | 'consultation'
  hours: number
  date: string
  notes?: string
  teacher_id: number
  schedule_slot_id?: string
}

export interface StudentProfile {
  id: number
  course: number
  user: User
  study_group?: { id: number; name: string }
}

export interface Notification {
  id: string
  title: string
  body: string
  read_at?: string
  created_at: string
}

export interface IntegrationLog {
  id: number
  direction: string
  entity_type: string
  status: string
  payload?: unknown
  created_at: string
}

export interface DashboardStats {
  funnel: Record<string, number>
  total: number
  recent_applicants: Applicant[]
}

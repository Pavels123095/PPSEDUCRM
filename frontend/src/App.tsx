import { BrowserRouter, Routes, Route, Navigate } from 'react-router-dom'
import { QueryClient, QueryClientProvider } from '@tanstack/react-query'
import { AuthProvider } from './context/AuthContext'
import { ProtectedRoute } from './routes/ProtectedRoute'
import { ResponsiveLayout } from './layouts/ResponsiveLayout'
import { LoginPage } from './pages/LoginPage'
import { ManagerDashboard } from './pages/manager/ManagerDashboard'
import { ApplicantsList } from './pages/manager/ApplicantsList'
import { ApplicantDetail } from './pages/manager/ApplicantDetail'
import { TeacherSchedule } from './pages/teacher/TeacherSchedule'
import { TeacherHours } from './pages/teacher/TeacherHours'
import { TeacherClassrooms } from './pages/teacher/TeacherClassrooms'
import { StudentSchedule } from './pages/student/StudentSchedule'
import { StudentProfile } from './pages/student/StudentProfile'
import { StudentNotifications } from './pages/student/StudentNotifications'
import { AdminIntegrations } from './pages/admin/AdminIntegrations'
import { AdminReferences } from './pages/admin/AdminReferences'

const queryClient = new QueryClient()

function AppRoutes() {
  return (
    <Routes>
      <Route path="/login" element={<LoginPage />} />
      <Route
        path="/manager"
        element={
          <ProtectedRoute roles={['manager', 'admin']}>
            <ResponsiveLayout><ManagerDashboard /></ResponsiveLayout>
          </ProtectedRoute>
        }
      />
      <Route
        path="/manager/applicants"
        element={
          <ProtectedRoute roles={['manager', 'admin']}>
            <ResponsiveLayout><ApplicantsList /></ResponsiveLayout>
          </ProtectedRoute>
        }
      />
      <Route
        path="/manager/applicants/:id"
        element={
          <ProtectedRoute roles={['manager', 'admin']}>
            <ResponsiveLayout><ApplicantDetail /></ResponsiveLayout>
          </ProtectedRoute>
        }
      />
      <Route
        path="/teacher/schedule"
        element={
          <ProtectedRoute roles={['teacher', 'admin']}>
            <ResponsiveLayout><TeacherSchedule /></ResponsiveLayout>
          </ProtectedRoute>
        }
      />
      <Route
        path="/teacher/hours"
        element={
          <ProtectedRoute roles={['teacher', 'admin']}>
            <ResponsiveLayout><TeacherHours /></ResponsiveLayout>
          </ProtectedRoute>
        }
      />
      <Route
        path="/teacher/classrooms"
        element={
          <ProtectedRoute roles={['teacher', 'admin']}>
            <ResponsiveLayout><TeacherClassrooms /></ResponsiveLayout>
          </ProtectedRoute>
        }
      />
      <Route
        path="/student"
        element={
          <ProtectedRoute roles={['student']}>
            <ResponsiveLayout><StudentSchedule /></ResponsiveLayout>
          </ProtectedRoute>
        }
      />
      <Route
        path="/student/profile"
        element={
          <ProtectedRoute roles={['student']}>
            <ResponsiveLayout><StudentProfile /></ResponsiveLayout>
          </ProtectedRoute>
        }
      />
      <Route
        path="/student/notifications"
        element={
          <ProtectedRoute roles={['student']}>
            <ResponsiveLayout><StudentNotifications /></ResponsiveLayout>
          </ProtectedRoute>
        }
      />
      <Route
        path="/admin/integrations"
        element={
          <ProtectedRoute roles={['admin']}>
            <ResponsiveLayout><AdminIntegrations /></ResponsiveLayout>
          </ProtectedRoute>
        }
      />
      <Route
        path="/admin/references"
        element={
          <ProtectedRoute roles={['admin']}>
            <ResponsiveLayout><AdminReferences /></ResponsiveLayout>
          </ProtectedRoute>
        }
      />
      <Route path="/" element={<Navigate to="/login" replace />} />
      <Route path="*" element={<Navigate to="/login" replace />} />
    </Routes>
  )
}

export default function App() {
  return (
    <QueryClientProvider client={queryClient}>
      <AuthProvider>
        <BrowserRouter>
          <AppRoutes />
        </BrowserRouter>
      </AuthProvider>
    </QueryClientProvider>
  )
}

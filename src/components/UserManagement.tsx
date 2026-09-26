import React, { useState } from 'react';
import { useData } from '../context/DataContext';
import { ShieldCheck, UserCheck, UserX, CheckCircle2, AlertCircle, Search } from 'lucide-react';
import { UserRole } from '../types';

export const UserManagement: React.FC = () => {
  const { usersList, toggleUserActive, toggleUserApproved, updateUserRole } = useData();
  const [search, setSearch] = useState('');
  const [roleFilter, setRoleFilter] = useState<string>('ALL');

  const filteredUsers = usersList.filter(u => {
    const matchesSearch = 
      u.name.toLowerCase().includes(search.toLowerCase()) ||
      u.email.toLowerCase().includes(search.toLowerCase()) ||
      u.area_nombre.toLowerCase().includes(search.toLowerCase()) ||
      u.sede_nombre.toLowerCase().includes(search.toLowerCase());
    const matchesRole = roleFilter === 'ALL' || u.roles.includes(roleFilter as UserRole);
    return matchesSearch && matchesRole;
  });

  return (
    <div className="space-y-4">
      <div className="flex flex-col sm:flex-row sm:items-center justify-between gap-3 pb-3 border-b border-slate-200">
        <div>
          <h2 className="text-xl font-bold text-slate-900 tracking-tight flex items-center gap-2">
            <ShieldCheck className="w-5 h-5 text-red-600" />
            Control de Usuarios y Roles (RBAC)
          </h2>
          <p className="text-xs text-slate-500">
            Aprobación institucional, activación y asignación de roles multi-inquilino UNERG ({usersList.length} cuentas)
          </p>
        </div>
      </div>

      {/* Filter and Search Bar */}
      <div className="bg-white p-3.5 rounded-xl border border-slate-200 shadow-xs flex flex-col sm:flex-row gap-2.5 items-stretch sm:items-center justify-between">
        <div className="relative flex-1 max-w-md">
          <Search className="w-4 h-4 text-slate-400 absolute left-3 top-1/2 -translate-y-1/2" />
          <input
            type="text"
            placeholder="Buscar por usuario, correo, sede o área..."
            value={search}
            onChange={(e) => setSearch(e.target.value)}
            className="w-full pl-9 pr-3 py-1.5 text-xs bg-slate-50 border border-slate-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-unerg-blue"
          />
        </div>

        <div className="flex items-center gap-1.5 overflow-x-auto text-xs">
          <button
            onClick={() => setRoleFilter('ALL')}
            className={`px-2.5 py-1 rounded-lg text-xs font-semibold transition-colors ${
              roleFilter === 'ALL'
                ? 'bg-slate-800 text-white'
                : 'bg-slate-100 text-slate-600 hover:bg-slate-200'
            }`}
          >
            Todos ({usersList.length})
          </button>
          <button
            onClick={() => setRoleFilter('admin')}
            className={`px-2.5 py-1 rounded-lg text-xs font-semibold transition-colors ${
              roleFilter === 'admin'
                ? 'bg-red-600 text-white'
                : 'bg-red-50 text-red-700 hover:bg-red-100 border border-red-200'
            }`}
          >
            Admins ({usersList.filter(u => u.roles.includes('admin')).length})
          </button>
          <button
            onClick={() => setRoleFilter('area_manager')}
            className={`px-2.5 py-1 rounded-lg text-xs font-semibold transition-colors ${
              roleFilter === 'area_manager'
                ? 'bg-blue-600 text-white'
                : 'bg-blue-50 text-blue-700 hover:bg-blue-100 border border-blue-200'
            }`}
          >
            Jefes de Área ({usersList.filter(u => u.roles.includes('area_manager')).length})
          </button>
          <button
            onClick={() => setRoleFilter('teacher')}
            className={`px-2.5 py-1 rounded-lg text-xs font-semibold transition-colors ${
              roleFilter === 'teacher'
                ? 'bg-emerald-600 text-white'
                : 'bg-emerald-50 text-emerald-700 hover:bg-emerald-100 border border-emerald-200'
            }`}
          >
            Docentes ({usersList.filter(u => u.roles.includes('teacher')).length})
          </button>
        </div>
      </div>

      <div className="bg-white rounded-xl border border-slate-200 shadow-sm overflow-hidden">
        <div className="overflow-x-auto">
          <table className="w-full text-left text-xs">
            <thead className="bg-slate-100/70 border-b border-slate-200 text-slate-600 uppercase font-semibold text-[11px] tracking-wider">
              <tr>
                <th className="py-3 px-4">Usuario / Correo</th>
                <th className="py-3 px-4">Roles Asignados</th>
                <th className="py-3 px-4">Sede y Área</th>
                <th className="py-3 px-4">Estado Aprobación</th>
                <th className="py-3 px-4">Estado Activo</th>
                <th className="py-3 px-4 text-right">Acciones</th>
              </tr>
            </thead>
            <tbody className="divide-y divide-slate-100">
              {filteredUsers.length === 0 ? (
                <tr>
                  <td colSpan={6} className="py-8 text-center text-slate-500">
                    No se encontraron usuarios con los criterios de búsqueda.
                  </td>
                </tr>
              ) : (
                filteredUsers.map(u => (
                <tr key={u.id} className="hover:bg-slate-50/80 transition-colors">
                  <td className="py-3 px-4">
                    <div className="font-bold text-slate-900 text-sm">{u.name}</div>
                    <div className="text-slate-500 text-[11px] font-mono">{u.email}</div>
                  </td>

                  <td className="py-3 px-4">
                    <div className="flex flex-wrap gap-1">
                      {u.roles.map(r => (
                        <span
                          key={r}
                          className={`px-2 py-0.5 rounded text-[10px] font-bold ${
                            r === 'admin'
                              ? 'bg-red-100 text-red-800'
                              : r === 'area_manager'
                              ? 'bg-blue-100 text-blue-800'
                              : 'bg-emerald-100 text-emerald-800'
                          }`}
                        >
                          {r === 'admin' ? 'Admin General' : r === 'area_manager' ? 'Jefe de Área' : 'Docente'}
                        </span>
                      ))}
                    </div>
                  </td>

                  <td className="py-3 px-4">
                    <div className="font-medium text-slate-800">{u.sede_nombre}</div>
                    <div className="text-[11px] text-slate-500">{u.area_nombre}</div>
                  </td>

                  <td className="py-3 px-4">
                    <button
                      onClick={() => toggleUserApproved(u.id)}
                      className={`inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-[11px] font-semibold transition-colors ${
                        u.is_approved
                          ? 'bg-emerald-50 text-emerald-700 border border-emerald-200 hover:bg-emerald-100'
                          : 'bg-amber-50 text-amber-700 border border-amber-200 hover:bg-amber-100'
                      }`}
                    >
                      {u.is_approved ? <CheckCircle2 className="w-3.5 h-3.5" /> : <AlertCircle className="w-3.5 h-3.5" />}
                      {u.is_approved ? 'Aprobado' : 'Pendiente'}
                    </button>
                  </td>

                  <td className="py-3 px-4">
                    <button
                      onClick={() => toggleUserActive(u.id)}
                      className={`inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-[11px] font-semibold transition-colors ${
                        u.is_active
                          ? 'bg-blue-50 text-blue-700 border border-blue-200 hover:bg-blue-100'
                          : 'bg-slate-100 text-slate-600 border border-slate-200 hover:bg-slate-200'
                      }`}
                    >
                      {u.is_active ? <UserCheck className="w-3.5 h-3.5" /> : <UserX className="w-3.5 h-3.5" />}
                      {u.is_active ? 'Activo' : 'Inactivo'}
                    </button>
                  </td>

                  <td className="py-3 px-4 text-right">
                    <div className="flex items-center justify-end gap-1">
                      <select
                        value={u.roles[0]}
                        onChange={(e) => {
                          const newPrimary = e.target.value as UserRole;
                          // Keep existing roles or replace primary
                          const updated = Array.from(new Set([newPrimary, ...u.roles]));
                          updateUserRole(u.id, updated as UserRole[]);
                        }}
                        className="text-[11px] p-1 border border-slate-300 rounded bg-white text-slate-700 font-medium"
                      >
                        <option value="admin">Asignar Admin</option>
                        <option value="area_manager">Asignar Jefe Área</option>
                        <option value="teacher">Asignar Docente</option>
                      </select>
                    </div>
                  </td>
                </tr>
              )))}
            </tbody>
          </table>
        </div>
      </div>
    </div>
  );
};

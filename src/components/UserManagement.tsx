import React from 'react';
import { useData } from '../context/DataContext';
import { ShieldCheck, UserCheck, UserX, CheckCircle2, AlertCircle } from 'lucide-react';
import { UserRole } from '../types';

export const UserManagement: React.FC = () => {
  const { usersList, toggleUserActive, toggleUserApproved, updateUserRole } = useData();

  return (
    <div className="space-y-4">
      <div className="flex flex-col sm:flex-row sm:items-center justify-between gap-3 pb-3 border-b border-slate-200">
        <div>
          <h2 className="text-xl font-bold text-slate-900 tracking-tight flex items-center gap-2">
            <ShieldCheck className="w-5 h-5 text-red-600" />
            Control de Usuarios y Roles (RBAC)
          </h2>
          <p className="text-xs text-slate-500">
            Aprobación institucional, activación y asignación de roles multi-inquilino UNERG
          </p>
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
              {usersList.map(u => (
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
              ))}
            </tbody>
          </table>
        </div>
      </div>
    </div>
  );
};

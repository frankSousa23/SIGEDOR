import React, { useState } from 'react';
import { useApp } from '../context/AppContext';
import { User, UserRole } from '../types';
import {
  Users,
  Search,
  Plus,
  Shield,
  CheckCircle2,
  XCircle,
  Edit2,
  Trash2,
  X,
  Mail,
  Building2
} from 'lucide-react';

export const UsersView: React.FC = () => {
  const {
    users,
    sedes,
    areas,
    addUser,
    updateUser,
    deleteUser,
    toggleUserActive,
    toggleUserApproved,
    currentUser
  } = useApp();

  const [searchTerm, setSearchTerm] = useState('');
  const [selectedRoleFilter, setSelectedRoleFilter] = useState('');
  const [isModalOpen, setIsModalOpen] = useState(false);
  const [editingUser, setEditingUser] = useState<User | null>(null);

  // Form
  const [formData, setFormData] = useState<{
    name: string;
    email: string;
    sede_nombre: string;
    area_nombre: string;
    roles: UserRole[];
    is_active: boolean;
    is_approved: boolean;
  }>({
    name: '',
    email: '',
    sede_nombre: sedes[0]?.nombre || '',
    area_nombre: areas[0]?.nombre || '',
    roles: ['teacher'],
    is_active: true,
    is_approved: true,
  });

  const handleOpenCreateModal = () => {
    setEditingUser(null);
    setFormData({
      name: '',
      email: '',
      sede_nombre: sedes[0]?.nombre || '',
      area_nombre: areas[0]?.nombre || '',
      roles: ['teacher'],
      is_active: true,
      is_approved: true,
    });
    setIsModalOpen(true);
  };

  const handleOpenEditModal = (u: User) => {
    setEditingUser(u);
    setFormData({
      name: u.name,
      email: u.email,
      sede_nombre: u.sede_nombre,
      area_nombre: u.area_nombre,
      roles: u.roles,
      is_active: u.is_active,
      is_approved: u.is_approved,
    });
    setIsModalOpen(true);
  };

  const handleFormSubmit = (e: React.FormEvent) => {
    e.preventDefault();
    const matchedSede = sedes.find(s => s.nombre === formData.sede_nombre);
    const matchedArea = areas.find(a => a.nombre === formData.area_nombre);

    const userPayload = {
      ...formData,
      sede_id: matchedSede?.id,
      area_id: matchedArea?.id,
    };

    if (editingUser) {
      updateUser(editingUser.id, userPayload);
    } else {
      addUser(userPayload);
    }
    setIsModalOpen(false);
  };

  const handleRoleToggle = (role: UserRole) => {
    setFormData(prev => {
      const exists = prev.roles.includes(role);
      const newRoles = exists
        ? prev.roles.filter(r => r !== role)
        : [...prev.roles, role];
      return { ...prev, roles: newRoles.length > 0 ? newRoles : ['teacher'] };
    });
  };

  const filteredUsers = users.filter(u => {
    const matchesSearch =
      u.name.toLowerCase().includes(searchTerm.toLowerCase()) ||
      u.email.toLowerCase().includes(searchTerm.toLowerCase()) ||
      u.area_nombre.toLowerCase().includes(searchTerm.toLowerCase());

    const matchesRole = selectedRoleFilter ? u.roles.includes(selectedRoleFilter as UserRole) : true;

    return matchesSearch && matchesRole;
  });

  return (
    <div className="space-y-6">
      {/* Header */}
      <div className="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
          <h1 className="text-xl font-bold text-slate-900 font-serif">Usuarios y Permisos del Sistema</h1>
          <p className="text-xs text-slate-500">Gestión de cuentas institucionales, asignación de roles de acceso y aprobación de usuarios</p>
        </div>

        <button
          onClick={handleOpenCreateModal}
          className="bg-[#003366] hover:bg-[#002244] text-white text-xs font-bold px-3.5 py-2 rounded-lg transition-colors flex items-center space-x-1.5 shadow-sm"
        >
          <Plus className="w-4 h-4 text-amber-400" />
          <span>Nuevo Usuario</span>
        </button>
      </div>

      {/* Search and Filters */}
      <div className="bg-white p-4 rounded-xl border border-slate-200 shadow-sm flex flex-col sm:flex-row gap-3 items-center justify-between">
        <div className="flex flex-1 gap-3 w-full sm:w-auto">
          <div className="relative flex-1">
            <Search className="w-4 h-4 text-slate-400 absolute left-3 top-2.5" />
            <input
              type="text"
              placeholder="Buscar por nombre, correo electrónico o área..."
              value={searchTerm}
              onChange={(e) => setSearchTerm(e.target.value)}
              className="w-full pl-9 pr-3 py-2 border border-slate-300 rounded-lg text-xs focus:ring-2 focus:ring-[#003366] focus:outline-none"
            />
          </div>

          <select
            value={selectedRoleFilter}
            onChange={(e) => setSelectedRoleFilter(e.target.value)}
            className="py-2 px-3 border border-slate-300 rounded-lg text-xs focus:ring-2 focus:ring-[#003366] focus:outline-none text-slate-700"
          >
            <option value="">Todos los Roles</option>
            <option value="admin">Administrador General</option>
            <option value="area_manager">Gestor de Área</option>
            <option value="teacher">Docente</option>
            <option value="panel_user">Usuario de Consulta</option>
          </select>
        </div>
      </div>

      {/* Table */}
      <div className="bg-white rounded-xl border border-slate-200 shadow-sm overflow-hidden">
        <div className="overflow-x-auto">
          <table className="w-full text-left text-xs text-slate-700">
            <thead className="bg-[#003366] text-white uppercase text-[11px] font-semibold tracking-wider">
              <tr>
                <th className="py-3 px-4">Usuario / Nombre</th>
                <th className="py-3 px-4">Correo Institucional</th>
                <th className="py-3 px-4">Sede y Área Asignada</th>
                <th className="py-3 px-4">Roles de Acceso</th>
                <th className="py-3 px-4 text-center">Estado / Aprobación</th>
                <th className="py-3 px-4 text-center">Acciones</th>
              </tr>
            </thead>
            <tbody className="divide-y divide-slate-100 font-sans">
              {filteredUsers.length === 0 ? (
                <tr>
                  <td colSpan={6} className="text-center py-8 text-slate-500">
                    No se encontraron usuarios con los criterios especificados.
                  </td>
                </tr>
              ) : (
                filteredUsers.map(u => (
                  <tr key={u.id} className="hover:bg-slate-50 transition-colors">
                    <td className="py-3 px-4">
                      <div className="font-bold text-slate-900">{u.name}</div>
                      <div className="text-[11px] text-slate-500 font-mono">ID #{u.id}</div>
                    </td>

                    <td className="py-3 px-4">
                      <div className="text-slate-800 font-medium">{u.email}</div>
                    </td>

                    <td className="py-3 px-4">
                      <div className="font-medium text-slate-800">{u.area_nombre}</div>
                      <div className="text-[11px] text-slate-500">{u.sede_nombre}</div>
                    </td>

                    <td className="py-3 px-4">
                      <div className="flex flex-wrap gap-1">
                        {u.roles.map(r => (
                          <span
                            key={r}
                            className={`px-2 py-0.5 rounded text-[10px] font-bold ${
                              r === 'admin' ? 'bg-amber-100 text-amber-900 border border-amber-300' :
                              r === 'area_manager' ? 'bg-blue-100 text-blue-900' :
                              r === 'teacher' ? 'bg-emerald-100 text-emerald-900' :
                              'bg-slate-100 text-slate-800'
                            }`}
                          >
                            {r === 'admin' ? 'Administrador' :
                             r === 'area_manager' ? 'Gestor de Área' :
                             r === 'teacher' ? 'Docente' : r}
                          </span>
                        ))}
                      </div>
                    </td>

                    <td className="py-3 px-4 text-center">
                      <div className="flex items-center justify-center space-x-1.5">
                        <button
                          onClick={() => toggleUserActive(u.id)}
                          className={`px-2 py-0.5 rounded text-[10px] font-bold inline-flex items-center space-x-1 transition-colors ${
                            u.is_active ? 'bg-emerald-100 text-emerald-800 hover:bg-emerald-200' : 'bg-slate-200 text-slate-600 hover:bg-slate-300'
                          }`}
                          title="Cambiar estado activo"
                        >
                          {u.is_active ? <CheckCircle2 className="w-3 h-3 text-emerald-600" /> : <XCircle className="w-3 h-3 text-slate-400" />}
                          <span>{u.is_active ? 'Activo' : 'Inactivo'}</span>
                        </button>

                        <button
                          onClick={() => toggleUserApproved(u.id)}
                          className={`px-2 py-0.5 rounded text-[10px] font-bold inline-flex items-center space-x-1 transition-colors ${
                            u.is_approved ? 'bg-blue-100 text-blue-800 hover:bg-blue-200' : 'bg-amber-100 text-amber-800 hover:bg-amber-200'
                          }`}
                          title="Cambiar estado de aprobación"
                        >
                          <span>{u.is_approved ? 'Aprobado' : 'Por Aprobar'}</span>
                        </button>
                      </div>
                    </td>

                    <td className="py-3 px-4 text-center">
                      <div className="flex items-center justify-center space-x-1.5">
                        <button
                          onClick={() => handleOpenEditModal(u)}
                          className="p-1.5 text-slate-600 hover:bg-slate-100 rounded-lg transition-colors"
                          title="Editar Usuario"
                        >
                          <Edit2 className="w-4 h-4" />
                        </button>
                        <button
                          onClick={() => {
                            if (confirm(`¿Está seguro de eliminar al usuario ${u.name}?`)) {
                              deleteUser(u.id);
                            }
                          }}
                          className="p-1.5 text-red-600 hover:bg-red-50 rounded-lg transition-colors"
                          title="Eliminar Usuario"
                        >
                          <Trash2 className="w-4 h-4" />
                        </button>
                      </div>
                    </td>
                  </tr>
                ))
              )}
            </tbody>
          </table>
        </div>
      </div>

      {/* Modal */}
      {isModalOpen && (
        <div className="fixed inset-0 bg-slate-950/70 backdrop-blur-sm z-50 flex items-center justify-center p-4 overflow-y-auto">
          <div className="bg-white rounded-xl shadow-2xl w-full max-w-2xl overflow-hidden border border-slate-300">
            <div className="bg-[#003366] text-white px-6 py-4 flex items-center justify-between border-b border-amber-500">
              <h3 className="font-bold text-base">{editingUser ? 'Editar Cuenta de Usuario' : 'Registrar Nuevo Usuario'}</h3>
              <button onClick={() => setIsModalOpen(false)} className="text-slate-300 hover:text-white">
                <X className="w-5 h-5" />
              </button>
            </div>

            <form onSubmit={handleFormSubmit} className="p-6 space-y-4 text-xs text-slate-700">
              <div className="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                  <label className="block font-semibold text-slate-800 mb-1">Nombre Completo *</label>
                  <input
                    type="text"
                    required
                    value={formData.name}
                    onChange={(e) => setFormData({ ...formData, name: e.target.value })}
                    className="w-full p-2 border border-slate-300 rounded focus:ring-2 focus:ring-[#003366] focus:outline-none"
                    placeholder="Ej. Ing. Roberto Gómez"
                  />
                </div>

                <div>
                  <label className="block font-semibold text-slate-800 mb-1">Correo Institucional *</label>
                  <input
                    type="email"
                    required
                    value={formData.email}
                    onChange={(e) => setFormData({ ...formData, email: e.target.value })}
                    className="w-full p-2 border border-slate-300 rounded focus:ring-2 focus:ring-[#003366] focus:outline-none"
                    placeholder="usuario@unerg.edu.ve"
                  />
                </div>

                <div>
                  <label className="block font-semibold text-slate-800 mb-1">Sede de Adscripción</label>
                  <select
                    value={formData.sede_nombre}
                    onChange={(e) => setFormData({ ...formData, sede_nombre: e.target.value })}
                    className="w-full p-2 border border-slate-300 rounded focus:ring-2 focus:ring-[#003366] focus:outline-none"
                  >
                    {sedes.map(s => (
                      <option key={s.id} value={s.nombre}>{s.nombre}</option>
                    ))}
                  </select>
                </div>

                <div>
                  <label className="block font-semibold text-slate-800 mb-1">Área Académica</label>
                  <select
                    value={formData.area_nombre}
                    onChange={(e) => setFormData({ ...formData, area_nombre: e.target.value })}
                    className="w-full p-2 border border-slate-300 rounded focus:ring-2 focus:ring-[#003366] focus:outline-none"
                  >
                    {areas.map(a => (
                      <option key={a.id} value={a.nombre}>{a.nombre}</option>
                    ))}
                  </select>
                </div>

                <div className="sm:col-span-2">
                  <label className="block font-semibold text-slate-800 mb-2">Roles y Permisos Asignados</label>
                  <div className="flex flex-wrap gap-3">
                    {(['admin', 'area_manager', 'teacher', 'panel_user'] as UserRole[]).map(role => (
                      <label
                        key={role}
                        className={`flex items-center space-x-2 p-2 border rounded-lg cursor-pointer transition-colors ${
                          formData.roles.includes(role) ? 'bg-amber-50 border-amber-300 text-[#003366]' : 'bg-slate-50 border-slate-200 text-slate-600'
                        }`}
                      >
                        <input
                          type="checkbox"
                          checked={formData.roles.includes(role)}
                          onChange={() => handleRoleToggle(role)}
                          className="h-4 w-4 text-[#003366] rounded focus:ring-[#003366]"
                        />
                        <span className="font-semibold text-xs">
                          {role === 'admin' ? 'Administrador General' :
                           role === 'area_manager' ? 'Gestor de Área' :
                           role === 'teacher' ? 'Docente' : 'Panel de Consulta'}
                        </span>
                      </label>
                    ))}
                  </div>
                </div>

                <div className="flex items-center space-x-2">
                  <input
                    type="checkbox"
                    id="userActive"
                    checked={formData.is_active}
                    onChange={(e) => setFormData({ ...formData, is_active: e.target.checked })}
                    className="h-4 w-4 text-[#003366] rounded focus:ring-[#003366]"
                  />
                  <label htmlFor="userActive" className="text-slate-800 font-medium cursor-pointer">
                    Cuenta Activa
                  </label>
                </div>

                <div className="flex items-center space-x-2">
                  <input
                    type="checkbox"
                    id="userApproved"
                    checked={formData.is_approved}
                    onChange={(e) => setFormData({ ...formData, is_approved: e.target.checked })}
                    className="h-4 w-4 text-[#003366] rounded focus:ring-[#003366]"
                  />
                  <label htmlFor="userApproved" className="text-slate-800 font-medium cursor-pointer">
                    Cuenta Aprobada por Administración
                  </label>
                </div>
              </div>

              <div className="flex justify-end space-x-2 pt-4 border-t border-slate-200">
                <button
                  type="button"
                  onClick={() => setIsModalOpen(false)}
                  className="px-4 py-2 border border-slate-300 rounded text-slate-700 hover:bg-slate-50 transition-colors"
                >
                  Cancelar
                </button>
                <button
                  type="submit"
                  className="px-4 py-2 bg-[#003366] hover:bg-[#002244] text-white font-bold rounded transition-colors shadow"
                >
                  {editingUser ? 'Guardar Cambios' : 'Crear Usuario'}
                </button>
              </div>
            </form>
          </div>
        </div>
      )}
    </div>
  );
};

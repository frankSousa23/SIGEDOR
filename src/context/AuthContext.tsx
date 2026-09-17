import React, { createContext, useContext, useState } from 'react';
import { User, UserRole } from '../types';
import { INITIAL_USERS } from '../data/initialData';

interface AuthContextType {
  currentUser: User;
  activeRole: UserRole;
  users: User[];
  setCurrentUser: (user: User) => void;
  setActiveRole: (role: UserRole) => void;
  switchUserById: (id: number) => void;
  login: (email: string) => boolean;
  logout: () => void;
  isSuperAdmin: boolean;
  isAreaManager: boolean;
  isTeacher: boolean;
}

const AuthContext = createContext<AuthContextType | undefined>(undefined);

export const AuthProvider: React.FC<{ children: React.ReactNode }> = ({ children }) => {
  const [users, setUsers] = useState<User[]>(INITIAL_USERS);
  // Default to Admin General
  const [currentUser, setCurrentUser] = useState<User>(INITIAL_USERS[0]);
  const [activeRole, setActiveRole] = useState<UserRole>(INITIAL_USERS[0].roles[0]);

  const switchUserById = (id: number) => {
    const target = users.find(u => u.id === id);
    if (target) {
      setCurrentUser(target);
      setActiveRole(target.roles[0]);
    }
  };

  const login = (email: string): boolean => {
    const user = users.find(u => u.email.toLowerCase() === email.toLowerCase());
    if (user && user.is_active && user.is_approved) {
      setCurrentUser(user);
      setActiveRole(user.roles[0]);
      return true;
    }
    return false;
  };

  const logout = () => {
    // Revert to demo teacher or prompt
    switchUserById(1);
  };

  const isSuperAdmin = activeRole === 'admin';
  const isAreaManager = activeRole === 'area_manager';
  const isTeacher = activeRole === 'teacher';

  return (
    <AuthContext.Provider
      value={{
        currentUser,
        activeRole,
        users,
        setCurrentUser,
        setActiveRole,
        switchUserById,
        login,
        logout,
        isSuperAdmin,
        isAreaManager,
        isTeacher,
      }}
    >
      {children}
    </AuthContext.Provider>
  );
};

export const useAuth = () => {
  const context = useContext(AuthContext);
  if (!context) {
    throw new Error('useAuth must be used within an AuthProvider');
  }
  return context;
};

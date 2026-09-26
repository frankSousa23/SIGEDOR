import React, { createContext, useContext, useState } from 'react';
import { User, UserRole } from '../types';
import { INITIAL_USERS } from '../data/initialData';

interface AuthContextType {
  currentUser: User;
  activeRole: UserRole;
  users: User[];
  isSimulating: boolean;
  setCurrentUser: (user: User) => void;
  setActiveRole: (role: UserRole) => void;
  switchUserById: (id: number) => void;
  simulateUser: (user: User, role?: UserRole) => void;
  exitSimulation: () => void;
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
  const [isSimulating, setIsSimulating] = useState<boolean>(false);

  const switchUserById = (id: number) => {
    const target = users.find(u => u.id === id);
    if (target) {
      setCurrentUser(target);
      setActiveRole(target.roles[0]);
      setIsSimulating(target.id !== INITIAL_USERS[0].id);
    }
  };

  const simulateUser = (user: User, role?: UserRole) => {
    setCurrentUser(user);
    setActiveRole(role || user.roles[0]);
    setIsSimulating(user.id !== INITIAL_USERS[0].id);
  };

  const exitSimulation = () => {
    setCurrentUser(INITIAL_USERS[0]);
    setActiveRole(INITIAL_USERS[0].roles[0]);
    setIsSimulating(false);
  };

  const login = (email: string): boolean => {
    const user = users.find(u => u.email.toLowerCase() === email.toLowerCase());
    if (user && user.is_active && user.is_approved) {
      setCurrentUser(user);
      setActiveRole(user.roles[0]);
      setIsSimulating(user.id !== INITIAL_USERS[0].id);
      return true;
    }
    return false;
  };

  const logout = () => {
    // Reset session to default institutional user
    if (INITIAL_USERS.length > 0) {
      setCurrentUser(INITIAL_USERS[0]);
      setActiveRole(INITIAL_USERS[0].roles[0]);
      setIsSimulating(false);
    }
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
        isSimulating,
        setCurrentUser,
        setActiveRole,
        switchUserById,
        simulateUser,
        exitSimulation,
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

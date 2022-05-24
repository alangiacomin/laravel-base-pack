import { useCallback } from 'react';
import { useDispatch, useSelector } from 'react-redux';
import GROUP from '../models/groups';
import PERM from '../models/perms';
import { USER_ACTIONS } from '../reducers/userReducer';

const useUser = () => {
  const user = useSelector((state) => state.user);
  const dispatch = useDispatch();

  const isInGroup = useCallback((group) => {
    if (!group) {
      return false;
    }

    return !!(user.groups && user.groups.includes(group));
  }, [user.groups]);

  const isAdmin = isInGroup(GROUP.Admin);

  const isGuest = !(user.id);

  const hasPerm = useCallback((perm) => {
    if (!perm) {
      return true;
    }

    if (perm === PERM.GuestsOnly) {
      return isGuest;
    }

    if (perm === PERM.RegisteredOnly) {
      return !isGuest;
    }

    if (isAdmin) {
      return true;
    }

    return !!(user.perms && user.perms.includes(perm));
  }, [isAdmin, isGuest, user.perms]);

  const login = (userData) => {
    dispatch({ type: USER_ACTIONS.LOGGED_IN, payload: userData });
  };
  const logout = () => {
    dispatch({ type: USER_ACTIONS.LOGGED_OUT });
  };

  return {
    ...user,
    isGuest,
    isAdmin,
    hasPerm,
    isInGroup,
    login,
    logout,
  };
};

export default useUser;

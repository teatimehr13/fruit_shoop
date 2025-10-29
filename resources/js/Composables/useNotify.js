import { toast } from 'vue3-toastify';

const SUCCESS = new Set([200, 201, 202, 204]);

export function useNotify() {
  function notifyByStatus(status, msg = '') {
    if (SUCCESS.has(status)) {
      if (status !== 204) toast.success(msg || 'Success');
    } else {
      toast.error(msg || defaultError(status));
    }
  }

  function defaultError(status) {
    if (status === 401) return 'Unauthorized';
    if (status === 422) return 'Validation failed';
    if (status >= 500) return 'Server error';
    return 'Request failed';
  }

  return { notifyByStatus, toast }; // 需要時可直接用 toast.info 等
}

// resources/js/lib/api.js
import axios from 'axios';
import { useNotify } from '@/Composables/useNotify';

const api = axios.create({
    headers: { 'X-Requested-With': 'XMLHttpRequest' },
});

const SUCCESS_MAP = {
    post: '新增成功',
    put: '更新成功',
    patch: '更新成功',
    delete: '刪除成功',
};

api.interceptors.response.use(
    (res) => {
        const { notifyByStatus, toast } = useNotify();
        const method = (res.config.method || '').toLowerCase();

        // 只對變更資料的方法提示
        if (SUCCESS_MAP[method]) {
            // 自訂訊息優先，其次對應表，再 fallback
            const msg =
                res.config?.meta?.successMessage ||
                res.data?.message ||
                SUCCESS_MAP[method];

            // 204（常見於刪除）也視為成功
            if ([200, 201, 202, 204].includes(res.status)) toast.success(msg);
        }

        return res;
    },
    (err) => {
        const { toast } = useNotify();
        const status = err.response?.status ?? 0;
        const method = (err.config?.method || '').toLowerCase();

        // 依狀態碼分類錯誤訊息（可自行調整）
        let msg =
            err.response?.data?.message ||
            (status === 422 ? firstValidationMessage(err.response?.data?.errors) : '');

        if (!msg) {
            if (status === 401) msg = '未授權，請登入';
            else if (status === 403) msg = '無權限';
            else if (status === 404) msg = '資源不存在';
            else if (status >= 500) msg = '伺服器錯誤';
            else msg = '請求失敗';
        }

        // 自訂錯誤訊息
        msg = err.config?.meta?.errorMessage || msg;

        toast.error(msg);
        return Promise.reject(err);
    }
);

function firstValidationMessage(errors) {
    if (!errors || typeof errors !== 'object') return '';
    const first = Object.values(errors)[0];
    return Array.isArray(first) ? first[0] : String(first);
}

export default api;

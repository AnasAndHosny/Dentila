<?php

return [
    'notFound'                      => 'عذراً، :class غير موجود.',
    'notAuthorized'                 => 'عذراً، أنت لا تملك الصلاحية المطلوبة لاتخاذ هذا الإجراء.',
    'index_success'                 => 'تم جلب قائمة :class بنجاح.',
    'store_success'                 => 'تم إنشاء :class بنجاح.',
    'show_success'                  => 'تم جلب :class بنجاح.',
    'update_success'                => 'تم تحديث :class بنجاح.',
    'destroy_success'               => 'تم حذف :class بنجاح.',
    'banned'                        => 'عذراً, تم حظر حسابك.',
    'notVerified'                   => 'يرجى توثيق رقم هاتفك.',
    'appointment' => [
        'not_found'   => "لا يوجد مواعيد لنقلها.",
        'conflict'    => "هذا الموعد يتعارض مع موعد آخر للطبيب (التاريخ: :date، الوقت: :start - :end).",
        'invalid_transition' => 'لا يمكن الانتقال من حالة :from إلى حالة :to.',
        'updated_successfully' => 'تم تحديث حالة الموعد بنجاح إلى :to.',
    ],

    'queue' => [
        'added'   => 'تمت إضافة المريض إلى الطابور.',
        'removed' => 'تمت إزالة المريض من الطابور.',
        'updated' => 'تم تحديث حالة الدور إلى :status.',
    ],

    'queue_turn' => [
        'created_successfully' => 'تمت إضافة الدور إلى الطابور بنجاح',
        'updated_successfully' => 'تم تحديث الدور بنجاح.',
        'invalid_transition'   => 'لا يمكن الانتقال من :from إلى :to.',
        'list'                 => 'تم جلب الطابور بنجاح',
    ],

    // تسجيل الوصول إلى الموعد
    'appointment' => [
        'invalid_code'         => 'رمز تسجيل الوصول غير صحيح.',
        'checked_in_success'   => 'تم تسجيل وصولك بنجاح.',
        'patient_not_found'    => 'لا يوجد ملف مريض مرتبط بهذا المستخدم.',
        'no_scheduled'         => 'لا يوجد موعد مجدول.',
        'check_in_not_allowed' => 'يمكن تسجيل الوصول فقط قبل الموعد بـ 30 دقيقة وحتى بعده بـ 15 دقيقة (الوقت: :time).',
    ],
];

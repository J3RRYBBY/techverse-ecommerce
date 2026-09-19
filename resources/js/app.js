import './bootstrap';
import Alpine from 'alpinejs';
import Swal from 'sweetalert2';

import './password_toggle';
import './admin/category_modal';
import './admin/sidebar_toggle';
import './admin/create_category_dropdown';
import './admin/product_image';
import './admin/product_variant';
import './user/product_details';

window.Alpine = Alpine;

Alpine.start();

window.Swal = Swal;

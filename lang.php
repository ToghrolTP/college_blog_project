<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (isset($_GET['lang'])) {
    $_SESSION['lang'] = $_GET['lang'];
}

if (!isset($_SESSION['lang'])) {
    $_SESSION['lang'] = 'en';
}

$current_lang = $_SESSION['lang'];

$translations = array(
    'en' => array(
        'site_title' => 'Simple Blog',
        'home' => 'Home',
        'new_post' => 'New Post',
        'all_posts' => 'All Blog Posts',
        'create_post' => 'Create New Post',
        'edit_post' => 'Edit Post',
        'post_title' => 'Post Title',
        'category' => 'Category',
        'select_category' => 'Select a category',
        'content' => 'Post Content',
        'author' => 'Author Name',
        'cancel' => 'Cancel',
        'publish' => 'Publish Post',
        'update' => 'Update Post',
        'edit' => 'Edit',
        'delete' => 'Delete',
        'by' => 'By',
        'previous' => 'Previous',
        'next' => 'Next',
        'no_posts' => 'No posts yet. Create your first post!',
        'delete_confirm' => 'Are you sure you want to delete this post?',
        'success_created' => 'Success! Your post has been created successfully.',
        'success_updated' => 'Success! Your post has been updated successfully.',
        'success_deleted' => 'Success! Your post has been deleted successfully.',
        'title_required' => 'Title is required',
        'content_required' => 'Content is required',
        'author_required' => 'Author name is required',
        'category_required' => 'Please select a category',
        'enter_title' => 'Enter post title',
        'enter_content' => 'Write your post content here...',
        'enter_author' => 'Enter your name',
        'cat_technology' => 'Technology',
        'cat_lifestyle' => 'Lifestyle',
        'cat_travel' => 'Travel',
        'cat_food' => 'Food',
        'cat_education' => 'Education',
        'cat_other' => 'Other',
        'search' => 'Search',
        'search_placeholder' => 'Search posts by title, content, or author...',
        'search_results' => 'Search results for',
        'post_found' => 'post found',
        'posts_found' => 'posts found',
        'clear_search' => 'Clear',
        'no_search_results' => 'No posts found matching your search. Try different keywords.',
    ),
    'fa' => array(
        'site_title' => 'وبلاگ ساده',
        'home' => 'خانه',
        'new_post' => 'پست جدید',
        'all_posts' => 'همه پست‌ها',
        'create_post' => 'ایجاد پست جدید',
        'edit_post' => 'ویرایش پست',
        'post_title' => 'عنوان پست',
        'category' => 'دسته‌بندی',
        'select_category' => 'یک دسته‌بندی انتخاب کنید',
        'content' => 'محتوای پست',
        'author' => 'نام نویسنده',
        'cancel' => 'لغو',
        'publish' => 'انتشار پست',
        'update' => 'به‌روزرسانی پست',
        'edit' => 'ویرایش',
        'delete' => 'حذف',
        'by' => 'توسط',
        'previous' => 'قبلی',
        'next' => 'بعدی',
        'no_posts' => 'هنوز پستی وجود ندارد. اولین پست خود را ایجاد کنید!',
        'delete_confirm' => 'آیا مطمئن هستید که می‌خواهید این پست را حذف کنید؟',
        'success_created' => 'موفقیت! پست شما با موفقیت ایجاد شد.',
        'success_updated' => 'موفقیت! پست شما با موفقیت به‌روزرسانی شد.',
        'success_deleted' => 'موفقیت! پست شما با موفقیت حذف شد.',
        'title_required' => 'عنوان الزامی است',
        'content_required' => 'محتوا الزامی است',
        'author_required' => 'نام نویسنده الزامی است',
        'category_required' => 'لطفاً یک دسته‌بندی انتخاب کنید',
        'enter_title' => 'عنوان پست را وارد کنید',
        'enter_content' => 'محتوای پست خود را اینجا بنویسید...',
        'enter_author' => 'نام خود را وارد کنید',
        'cat_technology' => 'فناوری',
        'cat_lifestyle' => 'سبک زندگی',
        'cat_travel' => 'سفر',
        'cat_food' => 'غذا',
        'cat_education' => 'آموزش',
        'cat_other' => 'سایر',
        'search' => 'جستجو',
        'search_placeholder' => 'جستجو در عنوان، محتوا یا نویسنده...',
        'search_results' => 'نتایج جستجو برای',
        'post_found' => 'پست یافت شد',
        'posts_found' => 'پست یافت شد',
        'clear_search' => 'پاک کردن',
        'no_search_results' => 'هیچ پستی با جستجوی شما یافت نشد. کلمات دیگری را امتحان کنید.',
    )
);

function t($key) {
    global $translations, $current_lang;
    return $translations[$current_lang][$key] ?? $key;
}

function get_category_name($category_key) {
    if (empty($category_key)) {
        return t('cat_other');
    }
    return t('cat_' . $category_key);
}

function get_direction() {
    global $current_lang;
    return ($current_lang == 'fa') ? 'rtl' : 'ltr';
}

function get_other_lang() {
    global $current_lang;
    return ($current_lang == 'en') ? 'fa' : 'en';
}

function get_other_lang_name() {
    global $current_lang;
    return ($current_lang == 'en') ? 'فارسی' : 'English';
}
?>

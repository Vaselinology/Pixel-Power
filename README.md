# Pixel-Power
PHP project for university <br>
<h3>public pages</h3>


| Page             | Filename               | Description                                                |
| ---------------- | ---------------------- | ---------------------------------------------------------- |
| Home / Main Page | `index.php`            | Show featured products, blog posts, banners, etc.          |
| Products Listing | `shop.php`             | Browse all products by category/search.                    |
| Product Details  | `product.php?id=...`   | Detailed view with image, price, description, add to cart. |
| About Us         | `about.php`            | Info about the store/team.                                 |
| Contact Us       | `contact.php`          | Contact form for feedback/partnership.                     |
| Blog             | `blog.php`             | View blog posts written by admins.                         |
| View Blog Post   | `blog_post.php?id=...` | Full article page.                                         |

<h3>customer pages</h3>

```
| Page                | Filename               | Description                                       |
| ------------------- | ---------------------- | ------------------------------------------------- |
| Register            | `register.php`         | Customer signup form.                             |
| Login               | `login.php`            | Login form (Customer/Admin distinction via role). |
| Logout              | `logout.php`           | Ends session.                                     |
| Cart                | `cart.php`             | View/edit cart items.                             |
| Checkout (optional) | `checkout.php`         | Final review before placing order.                |
| Orders              | `orders.php`           | Customer views order history.                     |
| Currency Request    | `currency_request.php` | Submit new game currency requests.                |
| Profile (optional)  | `profile.php`          | Edit user info, view past messages, etc.          |
```
<h3>Admin Pages</h3>

```
| Page               | Filename                 | Description                               |
| ------------------ | ------------------------ | ----------------------------------------- |
| Admin Dashboard    | `admin/dashboard.php`    | Admin overview: stats, recent sales, etc. |
| Manage Products    | `admin/products.php`     | View, add, edit, delete products.         |
| Add/Edit Product   | `admin/product_form.php` | Shared for add/edit.                      |
| Manage Users       | `admin/users.php`        | View/delete customer accounts.            |
| Manage Orders      | `admin/orders.php`       | View all customer orders.                 |
| Manage Requests    | `admin/requests.php`     | Approve/decline game currency requests.   |
| Manage Messages    | `admin/messages.php`     | View feedback & partnership messages.     |
| Manage Blog Posts  | `admin/blog.php`         | Create/edit/delete blog posts.            |
| Add/Edit Blog Post | `admin/blog_form.php`    | Shared form.                              |
```
folder structure:
```
/project-root
│
├── index.php
├── shop.php
├── product.php
├── about.php
├── contact.php
├── blog.php
├── blog_post.php
│
├── register.php
├── login.php
├── logout.php
├── cart.php
├── orders.php
├── currency_request.php
├── profile.php
│
├── /admin
│   ├── dashboard.php
│   ├── products.php
│   ├── product_form.php
│   ├── users.php
│   ├── orders.php
│   ├── requests.php
│   ├── messages.php
│   ├── blog.php
│   └── blog_form.php
│
├── /includes         ← Shared PHP includes
│   ├── db.php        ← DB connection
│   ├── header.php
│   ├── footer.php
│   ├── auth.php      ← Login checks
│   └── functions.php
│
├── /assets
│   ├── /css
│   ├── /js
│   └── /images
│
└── /uploads          ← Product & blog images
```

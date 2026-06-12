# CTEC 227 — Project 2: The Blog

Your final project. You're taking the starter blog from class and growing it into a real, multi-page blog app using **PHP, PDO, and MySQL**.

Right now the starter does one thing: `blog.php` reads every post from the database and dumps them on one page. By the end, a visitor will be able to read individual posts and browse them filtered by **category** and **author**.

> 🎥 **Watch the Loom demo first:** https://www.loom.com/share/d952d4b02c2247258825333f172671bb
> The demo is the source of truth. **If anything below differs from the video, the video wins.**

---

## Step 1 — Get it running (do this first)

1. Put this project folder in your XAMPP `htdocs` folder.
2. Start **XAMPP**, then start **Apache** and **MySQL**.
3. Open phpMyAdmin: http://localhost/phpmyadmin
4. Create a new database named **`blog`** (all lowercase).
5. Select `blog` → click **Import** → upload the `.sql` file from the `sql/` folder.
6. Open `http://localhost/<your-repo-folder>/blog.php` — you should see the existing posts.

**Database connection error?** Open `inc/db_connect.inc.php` and confirm the settings match your XAMPP setup (usually host `localhost`, user `root`, empty password). Leave these at the defaults — don't commit a personal password.

---

## Step 2 — Know what's already there

```
.
├── blog.php                ← main page; lists all posts (already working)
├── css/                    ← stylesheets
├── inc/
│   ├── db_connect.inc.php  ← PDO connection to the `blog` database
│   └── navbar.inc.php      ← Bootstrap navbar include
├── sql/                    ← database dump — import this into phpMyAdmin
└── README.md               ← this file
```

**The database has four tables:**
- `post` — the blog posts
- `author` — who wrote them
- `category` — the categories
- `post_category` — a join table linking posts to categories (one post can have many categories)

---

## Step 3 — Build the pages

Here's the **suggested order** — each one builds on the last, so going top to bottom keeps you from getting stuck.

| # | Page | What it does |
|---|------|--------------|
| 1 | `blog.php` *(mostly done)* | Make each post **title link** to its single post page. For each post, show its categories as **comma-separated links** (not a bulleted list), with the correct **singular/plural label** — "Category" for one, "Categories" for more than one. |
| 2 | `post.php?id=3` | Show **one full post** by its `id` from the URL: title, author, formatted date, all its categories (as links), and the full content. |
| 3 | `category.php?id=…` | Show only the posts in **one category**. If a category has **no posts**, show a message like *"No posts were found for this category"* instead of a blank page. |
| 4 | `author.php?id=…` | Show all posts written by **one author**. |
| 5 | `inc/navbar.inc.php` | Update the navbar so users can **reach every page** you built. List categories in **ascending alphabetical order**. |

> 💡 Once `post.php` works, you've got the pattern for reading a single record from the URL — `category.php` and `author.php` are variations on that same idea (filter the list by a value passed in the URL).

---

## Rules that apply to every page

These aren't optional — they're how the project is graded:

- **PDO + prepared statements for every query.** No string concatenation in SQL. No `mysqli_*`.
- **Reuse shared pieces** with `require` / `include` (DB connection, navbar, header/footer).
- **Escape output** with `htmlspecialchars()` — anything coming from the URL or database gets escaped before it's printed, to prevent XSS.
- **Style with Bootstrap 5** (already linked) — clean and consistent across pages.
- **No PHP warnings or notices** visible in the browser.

---

## Working in your repo (Git)

This repo is yours through **GitHub Classroom** — develop in it directly, don't fork it elsewhere. Your latest commit on `main` at the deadline is what gets graded.

```bash
git add .
git commit -m "Add single post page"
git push
```

**Commit often** with clear messages. **Don't commit:** your personal DB password in `db_connect.inc.php`, `.DS_Store`, editor folders (`.vscode/`, `.idea/`), or anything from `xampp/`.

---

## Before you submit — checklist

- [ ] All pages load with **no PHP errors**
- [ ] Each post title links to its **single post page**
- [ ] Categories show as **comma-separated links** with correct singular/plural labels
- [ ] **Category** and **author** filter pages work
- [ ] An empty category shows a **"no posts found"** message, not a blank page
- [ ] **Navbar links** reach all your pages
- [ ] All SQL uses **PDO prepared statements**
- [ ] The `sql/` file still **imports cleanly** into a fresh `blog` database (so your instructor can run it)
- [ ] Your final commit is **pushed to `main`** on GitHub

---

## Extra credit (optional)

- **Pagination** on the main list (e.g. 5 posts per page)
- **Search** by title or content keyword
- Sort the post list by **newest** or **oldest** date

---

## Stuck?

1. **Re-watch the Loom demo** — most "how should this work?" questions are answered there.
2. **Blank page?** Check the PHP error log in XAMPP.
3. **Still stuck?** Bring the *exact error message* to class or office hours.

Good luck! 🚀

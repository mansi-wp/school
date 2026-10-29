<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Sign In | Apex Coaching ERP</title>

    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&family=JetBrains+Mono:wght@400;500;600&display=fallback" rel="stylesheet">
    
    <!-- Font Awesome Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    
    <!-- Bootstrap 5 CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">

    <style>
        :root {
            --bg-dark: #090d16;
            --card-bg: rgba(17, 24, 39, 0.88);
            --border-glow: rgba(99, 102, 241, 0.25);
            --primary-gradient: linear-gradient(135deg, #4f46e5 0%, #7c3aed 100%);
            --admin-color: #6366f1;
            --teacher-color: #10b981;
            --accountant-color: #f59e0b;
            --receptionist-color: #06b6d4;
            --student-color: #c026d3;
            --parent-color: #f43f5e;
        }

        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            background-color: var(--bg-dark);
            min-height: 100vh;
            color: #f3f4f6;
            position: relative;
            overflow-x: hidden;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 2rem 1rem;
        }

        /* Ambient Background Mesh Glows */
        .ambient-glow-1 {
            position: fixed;
            top: -15%;
            left: 10%;
            width: 550px;
            height: 550px;
            border-radius: 50%;
            background: radial-gradient(circle, rgba(99, 102, 241, 0.18) 0%, rgba(99, 102, 241, 0) 70%);
            filter: blur(80px);
            pointer-events: none;
            z-index: 0;
        }

        .ambient-glow-2 {
            position: fixed;
            bottom: -20%;
            right: 5%;
            width: 650px;
            height: 650px;
            border-radius: 50%;
            background: radial-gradient(circle, rgba(16, 185, 129, 0.14) 0%, rgba(16, 185, 129, 0) 70%);
            filter: blur(90px);
            pointer-events: none;
            z-index: 0;
        }

        .login-wrapper {
            width: 100%;
            max-width: 980px;
            position: relative;
            z-index: 10;
        }

        /* Main Glass Card */
        .glass-panel {
            background: var(--card-bg);
            backdrop-filter: blur(20px);
            -webkit-backdrop-filter: blur(20px);
            border: 1px solid rgba(255, 255, 255, 0.08);
            border-radius: 24px;
            box-shadow: 0 25px 60px -15px rgba(0, 0, 0, 0.65), 0 0 40px rgba(99, 102, 241, 0.1);
            overflow: hidden;
        }

        /* Role Pill Cards Grid */
        .role-pill {
            background: rgba(255, 255, 255, 0.03);
            border: 1.5px solid rgba(255, 255, 255, 0.08);
            border-radius: 14px;
            padding: 12px 14px;
            cursor: pointer;
            transition: all 0.22s cubic-bezier(0.4, 0, 0.2, 1);
            display: flex;
            align-items: center;
            gap: 12px;
            user-select: none;
            position: relative;
        }

        .role-pill:hover {
            background: rgba(255, 255, 255, 0.07);
            border-color: rgba(255, 255, 255, 0.2);
            transform: translateY(-2px);
        }

        .role-pill.active {
            background: rgba(255, 255, 255, 0.09);
            box-shadow: 0 8px 24px -4px rgba(0, 0, 0, 0.4);
        }

        /* Individual Role Styles */
        .role-pill.role-super-admin.active {
            border-color: var(--admin-color);
            box-shadow: 0 0 20px rgba(99, 102, 241, 0.35);
        }
        .role-pill.role-teacher.active {
            border-color: var(--teacher-color);
            box-shadow: 0 0 20px rgba(16, 185, 129, 0.35);
        }
        .role-pill.role-accountant.active {
            border-color: var(--accountant-color);
            box-shadow: 0 0 20px rgba(245, 158, 11, 0.35);
        }
        .role-pill.role-receptionist.active {
            border-color: var(--receptionist-color);
            box-shadow: 0 0 20px rgba(6, 182, 212, 0.35);
        }
        .role-pill.role-student.active {
            border-color: var(--student-color);
            box-shadow: 0 0 20px rgba(192, 38, 211, 0.35);
        }
        .role-pill.role-parent.active {
            border-color: var(--parent-color);
            box-shadow: 0 0 20px rgba(244, 63, 94, 0.35);
        }

        .role-icon-box {
            width: 42px;
            height: 42px;
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.15rem;
            flex-shrink: 0;
            transition: all 0.2s ease;
        }

        /* Form Inputs */
        .form-control-custom, .form-select-custom {
            background: rgba(15, 23, 42, 0.75);
            border: 1.5px solid rgba(255, 255, 255, 0.12);
            color: #ffffff;
            border-radius: 12px;
            padding: 12px 16px;
            font-size: 0.92rem;
            transition: all 0.2s ease;
        }

        .form-control-custom:focus, .form-select-custom:focus {
            background: rgba(15, 23, 42, 0.95);
            border-color: #6366f1;
            color: #ffffff;
            box-shadow: 0 0 0 4px rgba(99, 102, 241, 0.2);
            outline: none;
        }

        .form-control-custom::placeholder {
            color: #64748b;
        }

        /* Submit Button */
        .btn-submit {
            background: linear-gradient(135deg, #4f46e5 0%, #7c3aed 100%);
            color: #ffffff;
            font-weight: 700;
            padding: 13px 20px;
            border-radius: 12px;
            border: none;
            box-shadow: 0 10px 25px -5px rgba(99, 102, 241, 0.4);
            transition: all 0.2s ease;
        }

        .btn-submit:hover {
            background: linear-gradient(135deg, #4338ca 0%, #6d28d9 100%);
            transform: translateY(-1px);
            box-shadow: 0 14px 28px -5px rgba(99, 102, 241, 0.55);
            color: #ffffff;
        }

        .font-mono-code {
            font-family: 'JetBrains Mono', monospace;
        }
    </style>
</head>
<body>

    <!-- Ambient Mesh Glows -->
    <div class="ambient-glow-1"></div>
    <div class="ambient-glow-2"></div>

    <div class="login-wrapper">
        <div class="glass-panel p-4 p-md-5">
            <div class="row align-items-stretch g-4">
                
                <!-- Left Column: Branding & Role Selection Grid -->
                <div class="col-lg-7 border-end border-dark-subtle pe-lg-4">
                    <!-- Brand Header -->
                    <div class="d-flex align-items-center gap-3 mb-4">
                        <div style="width: 52px; height: 52px; border-radius: 14px; background: linear-gradient(135deg, #6366f1, #10b981); display: flex; align-items: center; justify-content: center; box-shadow: 0 8px 20px rgba(99, 102, 241, 0.35);">
                            <i class="fas fa-graduation-cap text-white fs-4"></i>
                        </div>
                        <div>
                            <h3 class="fw-bold mb-0 text-white tracking-tight">APEX PREMIER ACADEMY</h3>
                            <div class="text-secondary small">Comprehensive Coaching ERP & Academic Administration</div>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="text-uppercase small fw-bold text-secondary mb-2 d-flex justify-content-between align-items-center">
                            <span><i class="fas fa-user-shield me-1 text-primary"></i> Select Login Persona</span>
                            <span class="badge bg-indigo-subtle text-primary border border-primary-subtle text-xs">Click to Auto-Fill</span>
                        </label>

                        <!-- Role Selection Grid (6 Distinct Colored Cards) -->
                        <div class="row g-2" id="roleCardsGrid">
                            <!-- 1. Super Admin -->
                            <div class="col-sm-6">
                                <div class="role-pill role-super-admin active" 
                                     data-role="super-admin"
                                     data-email="admin@apexcoaching.edu"
                                     data-name="Super Administrator"
                                     data-desc="Complete unhindered authority across finance, courses, students & system audit"
                                     data-color="var(--admin-color)">
                                    <div class="role-icon-box" style="background: rgba(99, 102, 241, 0.2); color: #818cf8;">
                                        <i class="fas fa-crown"></i>
                                    </div>
                                    <div class="flex-grow-1 overflow-hidden">
                                        <div class="fw-bold text-white text-sm">Super Admin</div>
                                        <div class="text-secondary small text-truncate">Full System Access</div>
                                    </div>
                                    <i class="fas fa-check-circle text-primary check-indicator"></i>
                                </div>
                            </div>

                            <!-- 2. Faculty / Teacher -->
                            <div class="col-sm-6">
                                <div class="role-pill role-teacher" 
                                     data-role="teacher"
                                     data-email="vikram.sharma@apexcoaching.edu"
                                     data-name="Prof. Vikram Sharma (Faculty)"
                                     data-desc="Class attendance marking, question paper upload, marks entry & student performance"
                                     data-color="var(--teacher-color)">
                                    <div class="role-icon-box" style="background: rgba(16, 185, 129, 0.2); color: #34d399;">
                                        <i class="fas fa-chalkboard-teacher"></i>
                                    </div>
                                    <div class="flex-grow-1 overflow-hidden">
                                        <div class="fw-bold text-white text-sm">Faculty / Teacher</div>
                                        <div class="text-secondary small text-truncate">Attendance & Marks</div>
                                    </div>
                                    <i class="fas fa-check-circle text-success check-indicator d-none"></i>
                                </div>
                            </div>

                            <!-- 3. Chief Accountant -->
                            <div class="col-sm-6">
                                <div class="role-pill role-accountant" 
                                     data-role="accountant"
                                     data-email="priya.mehta@apexcoaching.edu"
                                     data-name="Priya Mehta (Chief Accountant)"
                                     data-desc="Fee collection cashier terminal, receipt issuance, late fee waivers & ledger analytics"
                                     data-color="var(--accountant-color)">
                                    <div class="role-icon-box" style="background: rgba(245, 158, 11, 0.2); color: #fbbf24;">
                                        <i class="fas fa-cash-register"></i>
                                    </div>
                                    <div class="flex-grow-1 overflow-hidden">
                                        <div class="fw-bold text-white text-sm">Accountant</div>
                                        <div class="text-secondary small text-truncate">Fee & Cashier</div>
                                    </div>
                                    <i class="fas fa-check-circle text-warning check-indicator d-none"></i>
                                </div>
                            </div>

                            <!-- 4. Front Desk Receptionist -->
                            <div class="col-sm-6">
                                <div class="role-pill role-receptionist" 
                                     data-role="receptionist"
                                     data-email="rohit.verma@apexcoaching.edu"
                                     data-name="Rohit Verma (Admissions Front Desk)"
                                     data-desc="Student admissions, inquiries, batch capacities, fee viewing & parent communication"
                                     data-color="var(--receptionist-color)">
                                    <div class="role-icon-box" style="background: rgba(6, 182, 212, 0.2); color: #22d3ee;">
                                        <i class="fas fa-headset"></i>
                                    </div>
                                    <div class="flex-grow-1 overflow-hidden">
                                        <div class="fw-bold text-white text-sm">Receptionist</div>
                                        <div class="text-secondary small text-truncate">Admissions Desk</div>
                                    </div>
                                    <i class="fas fa-check-circle text-info check-indicator d-none"></i>
                                </div>
                            </div>

                            <!-- 5. Student -->
                            <div class="col-sm-6">
                                <div class="role-pill role-student" 
                                     data-role="student"
                                     data-email="aarav.patel@student.apex.edu"
                                     data-name="Aarav Patel (Student)"
                                     data-desc="Personalized timetable, test rankings, percentile reports, and fee receipt downloads"
                                     data-color="var(--student-color)">
                                    <div class="role-icon-box" style="background: rgba(192, 38, 211, 0.2); color: #e879f9;">
                                        <i class="fas fa-user-graduate"></i>
                                    </div>
                                    <div class="flex-grow-1 overflow-hidden">
                                        <div class="fw-bold text-white text-sm">Student</div>
                                        <div class="text-secondary small text-truncate">Results & Schedule</div>
                                    </div>
                                    <i class="fas fa-check-circle text-purple check-indicator d-none" style="color: #e879f9;"></i>
                                </div>
                            </div>

                            <!-- 6. Parent Guardian -->
                            <div class="col-sm-6">
                                <div class="role-pill role-parent" 
                                     data-role="parent"
                                     data-email="suresh.patel@parent.apex.edu"
                                     data-name="Suresh Patel (Parent Guardian)"
                                     data-desc="Ward attendance tracking, daily alerts, exam percentile report card & installment payment"
                                     data-color="var(--parent-color)">
                                    <div class="role-icon-box" style="background: rgba(244, 63, 94, 0.2); color: #fb7185;">
                                        <i class="fas fa-users"></i>
                                    </div>
                                    <div class="flex-grow-1 overflow-hidden">
                                        <div class="fw-bold text-white text-sm">Parent Guardian</div>
                                        <div class="text-secondary small text-truncate">Ward Monitoring</div>
                                    </div>
                                    <i class="fas fa-check-circle check-indicator d-none" style="color: #fb7185;"></i>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Role Permissions Summary Banner -->
                    <div class="p-3 rounded-3 mt-3 border border-secondary-subtle" id="roleInfoBanner" style="background: rgba(99, 102, 241, 0.08);">
                        <div class="d-flex align-items-center gap-2 mb-1">
                            <span class="badge bg-primary text-white" id="roleBadge">SUPER ADMIN</span>
                            <span class="fw-bold text-white small" id="roleNameText">System Super Administrator</span>
                        </div>
                        <div class="text-secondary small" id="roleDescText">
                            Complete unhindered authority across finance, courses, students & system audit.
                        </div>
                    </div>
                </div>

                <!-- Right Column: Authentication Form -->
                <div class="col-lg-5 ps-lg-4 d-flex flex-column justify-content-center">
                    <div class="mb-4">
                        <h4 class="fw-bold text-white mb-1">Account Sign In</h4>
                        <p class="text-secondary small mb-0">Enter credentials or pick a role pill to auto-fill</p>
                    </div>

                    @if ($errors->any())
                        <div class="alert alert-danger py-2 px-3 small rounded-3 mb-3 border-0 bg-danger-subtle text-danger">
                            <ul class="mb-0 ps-3">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    @if (session('status'))
                        <div class="alert alert-success py-2 px-3 small rounded-3 mb-3 border-0 bg-success-subtle text-success">
                            {{ session('status') }}
                        </div>
                    @endif

                    <form action="{{ route('login') }}" method="POST" id="loginForm">
                        @csrf

                        <!-- Role Selector Dropdown -->
                        <div class="mb-3">
                            <label class="form-label text-secondary small fw-bold mb-1">
                                <i class="fas fa-id-badge me-1 text-primary"></i> Operating Role
                            </label>
                            <select name="role" id="role_select" class="form-select form-select-custom">
                                <option value="super-admin" selected>🛡️ Super Administrator (Full ERP)</option>
                                <option value="teacher">👨‍🏫 Faculty / Teacher Portal</option>
                                <option value="accountant">💳 Chief Accountant (Fees & Ledger)</option>
                                <option value="receptionist">📋 Front Desk / Receptionist</option>
                                <option value="student">🎓 Student Dashboard</option>
                                <option value="parent">👨‍👩‍👦 Parent Guardian Console</option>
                            </select>
                        </div>

                        <!-- Email Address Input -->
                        <div class="mb-3">
                            <label class="form-label text-secondary small fw-bold mb-1">
                                <i class="fas fa-envelope me-1 text-primary"></i> Official Email
                            </label>
                            <input type="email" 
                                   name="email" 
                                   id="email_input" 
                                   class="form-control form-control-custom font-mono-code" 
                                   placeholder="name@apexcoaching.edu" 
                                   value="{{ old('email', 'admin@apexcoaching.edu') }}" 
                                   required 
                                   autofocus>
                        </div>

                        <!-- Password Input with Eye Toggle -->
                        <div class="mb-3">
                            <div class="d-flex justify-content-between align-items-center mb-1">
                                <label class="form-label text-secondary small fw-bold mb-0">
                                    <i class="fas fa-lock me-1 text-primary"></i> Password
                                </label>
                                <a href="#" class="text-decoration-none text-xs text-primary fw-semibold">
                                    Forgot password?
                                </a>
                            </div>
                            <div class="position-relative">
                                <input type="password" 
                                       name="password" 
                                       id="password_input" 
                                       class="form-control form-control-custom font-mono-code pe-5" 
                                       placeholder="••••••••••••" 
                                       value="Password@123" 
                                       required>
                                <button type="button" 
                                        id="togglePasswordBtn" 
                                        class="btn position-absolute top-50 end-0 translate-middle-y text-secondary border-0 bg-transparent pe-3" 
                                        style="z-index: 5;">
                                    <i class="fas fa-eye" id="eyeIcon"></i>
                                </button>
                            </div>
                        </div>

                        <!-- Remember Me & Security Policy -->
                        <div class="d-flex justify-content-between align-items-center mb-4">
                            <div class="form-check">
                                <input class="form-check-input bg-dark border-secondary" type="checkbox" name="remember" id="remember" checked>
                                <label class="form-check-label text-secondary small" for="remember">
                                    Remember session
                                </label>
                            </div>
                            <span class="text-xs text-secondary font-monospace">
                                <i class="fas fa-shield-alt text-success me-1"></i> RBAC 2.0 Guarded
                            </span>
                        </div>

                        <!-- Submit Button -->
                        <button type="submit" class="btn btn-submit w-100 mb-3 d-flex align-items-center justify-content-center gap-2">
                            <span>Sign In to Console</span>
                            <i class="fas fa-arrow-right"></i>
                        </button>
                    </form>

                    <div class="p-2.5 rounded-3 border border-secondary-subtle bg-dark-subtle text-xs text-secondary text-center">
                        <i class="fas fa-lock text-warning me-1"></i> Public registration is disabled. Accounts are managed by administration.
                    </div>
                </div>

            </div>
        </div>
    </div>

    <!-- Bootstrap 5 JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

    <!-- Interactive Role Switcher & Autofill Script -->
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const rolePills = document.querySelectorAll('.role-pill');
            const roleSelect = document.getElementById('role_select');
            const emailInput = document.getElementById('email_input');
            const passwordInput = document.getElementById('password_input');
            const roleBadge = document.getElementById('roleBadge');
            const roleNameText = document.getElementById('roleNameText');
            const roleDescText = document.getElementById('roleDescText');
            const roleInfoBanner = document.getElementById('roleInfoBanner');

            const roleThemes = {
                'super-admin': {
                    name: 'System Super Administrator',
                    badge: 'SUPER ADMIN',
                    badgeBg: '#6366f1',
                    bannerBg: 'rgba(99, 102, 241, 0.08)',
                    desc: 'Complete unhindered authority across finance, courses, students & system audit.'
                },
                'teacher': {
                    name: 'Prof. Vikram Sharma (Faculty)',
                    badge: 'FACULTY / TEACHER',
                    badgeBg: '#10b981',
                    bannerBg: 'rgba(16, 185, 129, 0.08)',
                    desc: 'Class attendance marking, question paper upload, marks entry & student performance analytics.'
                },
                'accountant': {
                    name: 'Priya Mehta (Chief Accountant)',
                    badge: 'CHIEF ACCOUNTANT',
                    badgeBg: '#f59e0b',
                    bannerBg: 'rgba(245, 158, 11, 0.08)',
                    desc: 'Fee collection cashier terminal, receipt issuance, late fee waivers & ledger analytics.'
                },
                'receptionist': {
                    name: 'Rohit Verma (Admissions Front Desk)',
                    badge: 'RECEPTION DESK',
                    badgeBg: '#06b6d4',
                    bannerBg: 'rgba(6, 182, 212, 0.08)',
                    desc: 'Student admissions, inquiries, batch capacities, fee viewing & parent communication.'
                },
                'student': {
                    name: 'Aarav Patel (Student)',
                    badge: 'STUDENT PORTAL',
                    badgeBg: '#c026d3',
                    bannerBg: 'rgba(192, 38, 211, 0.08)',
                    desc: 'Personalized timetable, test rankings, percentile reports, and fee receipt downloads.'
                },
                'parent': {
                    name: 'Suresh Patel (Parent Guardian)',
                    badge: 'PARENT PORTAL',
                    badgeBg: '#f43f5e',
                    bannerBg: 'rgba(244, 63, 94, 0.08)',
                    desc: 'Ward attendance tracking, daily alerts, exam percentile report card & installment payment.'
                }
            };

            function activateRole(role) {
                const theme = roleThemes[role];
                if (!theme) return;

                // 1. Update pills
                rolePills.forEach(pill => {
                    const isTarget = pill.getAttribute('data-role') === role;
                    pill.classList.toggle('active', isTarget);
                    const indicator = pill.querySelector('.check-indicator');
                    if (indicator) {
                        indicator.classList.toggle('d-none', !isTarget);
                    }
                });

                // 2. Sync dropdown
                if (roleSelect) {
                    roleSelect.value = role;
                }

                // 3. Update Banner
                roleBadge.textContent = theme.badge;
                roleBadge.style.backgroundColor = theme.badgeBg;
                roleNameText.textContent = theme.name;
                roleDescText.textContent = theme.desc;
                roleInfoBanner.style.backgroundColor = theme.bannerBg;

                // 4. Autofill credentials
                const activePill = document.querySelector(`.role-pill[data-role="${role}"]`);
                if (activePill) {
                    emailInput.value = activePill.getAttribute('data-email');
                    passwordInput.value = 'Password@123';
                }
            }

            // Click listener for role pills
            rolePills.forEach(pill => {
                pill.addEventListener('click', () => {
                    const role = pill.getAttribute('data-role');
                    activateRole(role);
                });
            });

            // Change listener for role select dropdown
            if (roleSelect) {
                roleSelect.addEventListener('change', (e) => {
                    activateRole(e.target.value);
                });
            }

            // Password Show/Hide Toggle
            const togglePasswordBtn = document.getElementById('togglePasswordBtn');
            const eyeIcon = document.getElementById('eyeIcon');
            if (togglePasswordBtn && passwordInput && eyeIcon) {
                togglePasswordBtn.addEventListener('click', () => {
                    const isPassword = passwordInput.getAttribute('type') === 'password';
                    passwordInput.setAttribute('type', isPassword ? 'text' : 'password');
                    eyeIcon.classList.toggle('fa-eye', !isPassword);
                    eyeIcon.classList.toggle('fa-eye-slash', isPassword);
                });
            }
        });
    </script>
</body>
</html>

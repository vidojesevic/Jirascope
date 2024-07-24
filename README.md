# Jirascope

![Alt text](public/images/logo.png)

Jirascope is a robust project management tool, designed as a Jira clone, built with Laravel, Filament, Vue.js, and Inertia.js. This application facilitates seamless collaboration within teams, allowing admins to manage users, clients, projects, and tasks effectively.

## Table of Contents

- [Usage](#usage)
- [Features](#features)
- [Contributing](#contributing)
- [License](#license)

## Usage

### Team Management

1. **Create a Team**:
    The first step is to create a team. A team will have an admin who can manage team members and clients.

2. **Add Users**:
    The team admin can add users to the team and assign them different roles under parent roles:
    - Developer - Can modify just it's own tasks
    - Project Manager - Can create, modify and delette tasks, view clients...
    - Admin - No restriction
    - User - Regular user that cannot access /app routes

### Client and Project Management

1. **Create Clients**:
    Admins can create clients that the team will work with.

2. **Create Projects**:
    For each client, admins can create multiple projects.

3. **Create Tasks**:
    Within each project, tasks can be created and assigned to team members.

## Features

- **Team Management**:
    - Create and manage teams.
    - Assign roles to users.

- **Client Management**:
    - Create and manage clients.

- **Project Management**:
    - Create and manage projects for clients.

- **Task Management**:
    - Create and assign tasks within projects.

## Contributing

We welcome contributions to improve Jirascope. To contribute:

1. Fork the repository.
2. Create a new branch (`git checkout -b feature-branch`).
3. Commit your changes (`git commit -m 'Add new feature'`).
4. Push to the branch (`git push origin feature-branch`).
5. Open a pull request.

## License

Jirascope is open-source software licensed under the [MIT license](LICENSE).

---

Enjoy using Jirascope! For any questions or support, please contact our [support team](mailto:vidoje@vsevic.com).

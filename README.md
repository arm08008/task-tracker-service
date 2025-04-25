# Task Tracker API - DDD/CQRS Implementation

## Architecture Overview

HTTP Request → [Infrastructure: Controller] → [Application: Command/Query] → [Domain: Model/Service] ↔ [Infrastructure: Repository]

### Layers
1. **Application Layer**
    - Commands: Write operations (CreateTaskCommand, UpdateTaskStatusCommand, AssignTaskCommand)
    - Queries: Read operations (GetTasksQuery)
    - Handlers: Mediate between infrastructure and domain (CreateTaskHandler, UpdateTaskStatusHandler, GetTasksHandler)
    - DTOs: Data transfer objects for output|input (TaskDTO)
    - Services: Application services that encapsulate use case logic
      (Not required in this case because i used handlers, but it can be useful when implementing more complex business logic.)

2. **Domain Layer**
    - Models|Entities: Task, User
    - Value Objects: Immutable objects (TaskId, TaskStatus)
    - Repository Interfaces: Persistence contracts (TaskRepositoryInterface)
    - Domain Events: Events triggered by domain logic (not implemented in this case)

3. **Infrastructure Layer**
    - Repositories: Concrete implementations (InMemoryTaskRepository, for real DB use Doctrine)
    - Controllers: Entry points for HTTP requests, receive requests, send responses
    - Event Dispatchers: Process domain events (not implemented in this case)

### Domain-Driven Design
- Keep business rules separate from technical stuff like databases or APIs
- Put important logic inside main objects (like Task or User), not just data
- Use the same words in code that the business or team uses (so it's clear for everyone)

### CQRS
- Commands: modify state `CreateTaskCommand`
- Queries: fetch data `GetTasksQuery`
- Separate paths for read/write operations

### Repository Pattern
- `TaskRepositoryInterface` with multiple implementations
- Persistence ignorance in domain layer

## Getting Started

### Requirements
- PHP 8.1+
- Symfony 6.4+
- Composer

### Installation
- Install dependencies
```bash
composer install
```

- Run app
```bash
php -S 127.0.0.1:8000 -t public/
```
or if you have installed Symfony CLI
```bash
symfony server:start
```

### Additional Notes
For implementing task comments, we'll need to create a relationship between comments and tasks. 
Since we currently only have a Task model, we'll need to create a Comment entity when moving to real db.
We can use ORM entities to manage the relationship between tasks and comments.
For db integration, we already have the RepositoryInterface in our domain layer. 
To use Doctrine, we need to create Doctrine repository implementation in our infrastructure layer implementing the RepositoryInterface.

Regarding field validations i used commands to handle them which isn't the best approach. We could handle validations in DTOs as well.
In my opinion, it would be better to have separate validator classes for each read and write operation, and use them in handlers or services.
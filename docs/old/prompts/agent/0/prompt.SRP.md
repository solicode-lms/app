
# Code Quality Rules

## Single Responsibility Principle (SRP)
- **Strict Adherence**: Every class and method must have a single, well-defined responsibility.
- **Service Delegation**: Specialized logic must be delegated to the appropriate service (e.g., Task logic in TacheService, Competency logic in RealisationUaService).
- **Refactoring**: If a method performs multiple distinct actions (calculation, database update, synchronization), split it or delegate sub-tasks.
- **Redundancy Check**: Before implementing a feature, check if it already exists or if it overlaps with existing automated logic (e.g. hooks).

## Documentation (RDOC)
- Comments must accurately reflect the code's behavior.
- Use explicit method names that describe the action (e.g., `triggerTaskSynchronization` instead of `updateTasksNote` if it does more than updating notes).

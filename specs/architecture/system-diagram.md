# Vincentian Hub System Diagram

```mermaid
flowchart TD
Users --> GoogleAuth
GoogleAuth --> WordPress
WordPress --> VincentianHubPlugin
VincentianHubPlugin --> Dashboard
VincentianHubPlugin --> Announcements
VincentianHubPlugin --> Documents
VincentianHubPlugin --> Events
VincentianHubPlugin --> TargetingEngine
Documents --> GoogleDrive
Events --> CalendarExport
CalendarExport --> ICSFeed
```

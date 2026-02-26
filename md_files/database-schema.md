# Database Schema & Eloquent Models

This document catalogs the Eloquent models in the HRIS mapping the database tables, their attributes, and relationships. Models are heavily utilizing Domain-Driven Design under `App\Features\...`.

---

## Model: `User`
**Namespace:** `App\Models\User`  
**Table:** ``

### Relationships
| Method | Relation Type | Related Model |
|--------|---------------|---------------|
| `actor()` | `BelongsTo` | `......................... App\Models\User` |
| `creator()` | `BelongsTo` | `....................... App\Models\User` |
| `subdivisions()` | `HasMany` | `App\Features\Employees\Models\Subdivision` |
| `sections()` | `HasMany` | `.. App\Features\Employees\Models\Section` |
| `employees()` | `HasMany` | `App\Features\Employees\Models\Employee` |
| `user()` | `BelongsTo` | `.......................... App\Models\User` |
| `division()` | `BelongsTo` | `App\Features\Employees\Models\Division` |
| `subdivision()` | `BelongsTo` | `App\Features\Employees\Models\Subdivision` |
| `section()` | `BelongsTo` | `. App\Features\Employees\Models\Section` |
| `pds()` | `HasOne` | `.................. App\Features\Pds\Models\Pds` |
| `leaveCredits()` | `HasMany` | `App\Features\Leave\Models\LeaveCredit` |
| `division()` | `BelongsTo` | `App\Features\Employees\Models\Division` |
| `subdivision()` | `BelongsTo` | `App\Features\Employees\Models\Subdivision` |
| `employees()` | `HasMany` | `App\Features\Employees\Models\Employee` |
| `division()` | `BelongsTo` | `App\Features\Employees\Models\Division` |
| `sections()` | `HasMany` | `.. App\Features\Employees\Models\Section` |
| `credit()` | `BelongsTo` | `.. App\Features\Leave\Models\LeaveCredit` |
| `creator()` | `BelongsTo` | `....................... App\Models\User` |
| `employee()` | `BelongsTo` | `App\Features\Employees\Models\Employee` |
| `employee()` | `BelongsTo` | `App\Features\Employees\Models\Employee` |
| `adjustments()` | `HasMany` | `App\Features\Leave\Models\LeaveAdjustment` |
| `readers()` | `BelongsToMany` | `................... App\Models\User` |
| `employee()` | `BelongsTo` | `App\Features\Employees\Models\Employee` |
| `reviewer()` | `BelongsTo` | `...................... App\Models\User` |
| `personal()` | `HasOne` | `..... App\Features\Pds\Models\PdsPersonal` |
| `family()` | `HasOne` | `......... App\Features\Pds\Models\PdsFamily` |
| `children()` | `HasMany` | `....... App\Features\Pds\Models\PdsChild` |
| `education()` | `HasMany` | `.. App\Features\Pds\Models\PdsEducation` |
| `cscEligibility()` | `HasMany` | `App\Features\Pds\Models\PdsCscEligibility` |
| `workExperience()` | `HasMany` | `App\Features\Pds\Models\PdsWorkExperience` |
| `voluntaryWork()` | `HasMany` | `App\Features\Pds\Models\PdsVoluntaryWork` |
| `training()` | `HasMany` | `.... App\Features\Pds\Models\PdsTraining` |
| `otherInfo()` | `HasMany` | `.. App\Features\Pds\Models\PdsOtherInfo` |
| `references()` | `HasMany` | `. App\Features\Pds\Models\PdsReference` |
| `backgroundInfo()` | `HasOne` | `App\Features\Pds\Models\PdsBackgroundInfo` |
| `pds()` | `BelongsTo` | `............... App\Features\Pds\Models\Pds` |
| `pds()` | `BelongsTo` | `............... App\Features\Pds\Models\Pds` |
| `pds()` | `BelongsTo` | `............... App\Features\Pds\Models\Pds` |
| `pds()` | `BelongsTo` | `............... App\Features\Pds\Models\Pds` |
| `pds()` | `BelongsTo` | `............... App\Features\Pds\Models\Pds` |
| `pds()` | `BelongsTo` | `............... App\Features\Pds\Models\Pds` |
| `pds()` | `BelongsTo` | `............... App\Features\Pds\Models\Pds` |
| `pds()` | `BelongsTo` | `............... App\Features\Pds\Models\Pds` |
| `pds()` | `BelongsTo` | `............... App\Features\Pds\Models\Pds` |
| `pds()` | `BelongsTo` | `............... App\Features\Pds\Models\Pds` |
| `pds()` | `BelongsTo` | `............... App\Features\Pds\Models\Pds` |
| `employee()` | `BelongsTo` | `App\Features\Employees\Models\Employee` |
| `employee()` | `HasOne` | `.. App\Features\Employees\Models\Employee` |
| `readNotices()` | `BelongsToMany` | `App\Features\Notices\Models\Notice` |
| `notifications()` | `MorphMany` | `Illuminate\Notifications\DatabaseNotification` |

---


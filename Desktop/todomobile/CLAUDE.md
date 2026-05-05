# CLAUDE.md

This file provides guidance to Claude Code (claude.ai/code) when working with code in this repository.

## Workspace layout

All source code lives inside `todolist/`. The repo root is just a container.

```
todomobile/
└── todolist/               ← git repo root
    ├── backend/            ← Express + TypeScript + SQLite API
    ├── frontend/           ← React + Vite + Tailwind web app
    ├── mobilefrontend/     ← Expo (React Native) mobile app — scaffold only, no features yet
    ├── .claude/CLAUDE.md   ← detailed guidance for backend + web frontend
    └── README.md           ← quickstart and curl smoke-test
```

**Read `todolist/.claude/CLAUDE.md` before working on the backend or web frontend.** It covers architecture rules, security non-negotiables, conventions, and custom slash commands in full.

## Commands

All commands must be run from inside the relevant subdirectory.

```bash
# Backend (from todolist/backend/)
npm run dev          # tsx watch — hot reload on :4000
npm run typecheck    # tsc --noEmit
npm run lint         # eslint src --ext .ts
npm test             # vitest (single-shot)
npm test -- <path>   # single test file
npm run build        # tsc → dist/
npm start            # node dist/server.js (production)
npm run migrate      # apply new migration files
npm run sweep        # purge expired token_blacklist rows

# Web frontend (from todolist/frontend/)
npm run dev          # Vite dev server on :5173
npm run typecheck    # tsc --noEmit
npm run lint         # eslint src --ext .ts,.tsx
npm run build        # tsc -b && vite build → dist/

# Mobile frontend (from todolist/mobilefrontend/)
npx expo start       # Expo dev server (Android/iOS/Web)
npm run reset-project  # wipe starter code to blank app/
```

## Architecture overview

The backend and web frontend follow Clean Architecture with dependencies pointing inward:

```
Presentation → Application → Domain ← Infrastructure
```

- **Domain** (`backend/src/domain/`) — entities (`User`, `Todo`), repo interfaces, domain errors. Zero framework imports.
- **Application** (`backend/src/application/`) — one use case per file (`*.uc.ts`). Calls repo interfaces only.
- **Infrastructure** (`backend/src/infrastructure/`) — SQLite repos, bcrypt, JWT, Pino logger. Wired in `composition.ts`.
- **Presentation** (`backend/src/presentation/`) — Express routes, controllers (`*.ctrl.ts`), middleware (`*.mw.ts`), Zod validators (`*.schema.ts`).

All DI wiring happens in `backend/src/composition.ts`. Controllers receive use cases from `container.uc.*`; they never instantiate infra directly.

**Auth:** JWT HS256, 15-min TTL, stored in `httpOnly` + `Secure` + `SameSite=Strict` cookies. Logout blacklists the token's `jti` in SQLite.

**Mobile frontend** (`mobilefrontend/`) is a fresh `create-expo-app` scaffold using Expo Router (file-based routing under `app/`). It has no API integration or todo features yet — implementation has not started.

## Key constraints

- TypeScript `strict: true` everywhere. No `any`.
- All SQL through `better-sqlite3` prepared statements — never string-concatenated queries.
- Server re-validates every request with Zod even if the frontend already validated.
- IDs are UUID strings (`randomUUID()`), never auto-increment integers.
- Files stay under ~150 lines; one responsibility per file.
- Backend is ESM (`"type": "module"`); internal imports use `.js` extension even for `.ts` source files.
- Do not add frameworks, abstractions, or features beyond what `todolist/.claude/todo_blueprint.md` specifies.

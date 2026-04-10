# AI Assistant Support for Beartropy Permissions

Beartropy Permissions includes AI assistant integration to help you manage roles, permissions, and user assignments.

## Supported AI Assistants

### Claude Code / Cursor / Other AI Tools
- Universal guide with component reference
- Cursor rules for component suggestions
- Copy-paste ready examples

## Directory Structure

```
beartropy/permissions/
├── .claude/skills/
│   ├── bt-permissions-setup/
│   └── bt-permissions-component/
└── docs/
    ├── llms/                      # LLM reference docs
    ├── components/                # User reference docs
    └── ai-assistants/
        ├── README.md              # This file
        ├── BEARTROPY_GUIDE.md     # Universal AI guide
        ├── cursor/.cursorrules    # Cursor configuration
        └── examples/
            └── permissions.md     # Usage examples
```

## Quick Start

### Using with Cursor

```bash
cp vendor/beartropy/permissions/docs/ai-assistants/cursor/.cursorrules .cursorrules
```

### Using with Other AI Tools

Point your AI assistant to:
```
vendor/beartropy/permissions/docs/ai-assistants/BEARTROPY_GUIDE.md
```

## License

MIT License.

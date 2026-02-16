# Papergames.io UX flow diagram

Reference: [papergames.io](https://papergames.io/en/). Diagram covers homepage, game page, and shared room flow.

## Mermaid flowchart

```mermaid
flowchart TB
    subgraph entry["Entry"]
        A["Land on site"]
    end

    subgraph home["Home /en/"]
        H["Homepage"]
        H --> H1["Value props: Free, Multiplayer, Child friendly"]
        H --> H2["Hero + App store badges"]
        H --> H3["Feature blocks: Ranking, Play with friend, Tournaments, Shop"]
        H --> H4["Game grid: 6 games"]
    end

    subgraph nav_from_home["From Home"]
        H --> Shop["/en/shop"]
        H --> CreateT["/en/t/create-tournament"]
        H --> MyT["/en/t/my-tournaments"]
        H --> Pricing["/en/pricing/gruppo"]
        H --> Game["Game page (e.g. /en/tic-tac-toe)"]
        H --> Guides["/docs/game-guides/"]
        H --> Changelog["/en/changelog"]
        H --> Developers["developers.papergames.io"]
        H --> Ranka["/en/blog/ranka"]
    end

    subgraph game_page["Game page (e.g. /en/tic-tac-toe)"]
        G["Game page"]
        G --> G1["Thumbnail + tagline"]
        G --> G2["Play mode choices"]
    end

    subgraph play_modes["Play modes"]
        G2 --> Friend["Play with a friend"]
        G2 --> Robot["Play vs robot"]
        G2 --> Tournament["Create tournament"]
        G2 --> Online["Play online (random)"]
    end

    subgraph room_flow["Shared room flow"]
        Friend --> CreateRoom["Create private session"]
        CreateRoom --> RoomURL["Unique link: /en/r/{roomId}"]
        RoomURL --> Share["User shares link"]
        Share --> Joiner["Friend opens /en/r/{roomId}"]
        Joiner --> InGame["In-game: 2 players connected"]
        InGame --> Play["Play (e.g. Tic Tac Toe)"]
    end

    subgraph other_modes["Other outcomes"]
        Robot --> VsRobot["Single player vs AI"]
        Tournament --> TourPage["/en/t/create-tournament"]
        Online --> Match["Match with random player"]
    end

    subgraph persistent_nav["From any page"]
        H -.-> Header["Header nav"]
        G -.-> Header
        InGame -.-> Header
        Header --> H
        Header --> Game
        Header --> Shop
        Header --> CreateT
        Header --> MyT
    end

    A --> H
    H --> Game
    G --> Friend
    G --> Robot
    G --> Tournament
    G --> Online
```

## Simplified user paths

| Path | Steps |
|------|--------|
| **Home to game** | Home -> click game card -> Game page |
| **Play with friend** | Game page -> "Play with a friend" -> get /en/r/{id} -> share link -> friend opens -> both in game |
| **Play vs robot** | Game page -> "Play vs robot" -> single-player game |
| **Tournament** | Game page or Home -> "Create tournament" -> /en/t/create-tournament |
| **Random opponent** | Game page -> "Play online" -> matchmaking -> game |
| **Direct room link** | User receives /en/r/{roomId} -> opens URL -> joins existing session (wait or play) |

## URL structure (observed)

- **Home**: `/en/`
- **Game pages**: `/en/{game-slug}` (e.g. `tic-tac-toe`, `battleship`, `connect4`, `gomoku`, `chess`, `checkers`)
- **Shared room**: `/en/r/{roomId}` (e.g. `Zpdu9dX6A`, `T-BYp7tpLV`) — play-with-a-friend session
- **Tournaments**: `/en/t/create-tournament`, `/en/t/my-tournaments`
- **Other**: `/en/shop`, `/en/pricing/gruppo`, `/docs/game-guides/`, `/en/changelog`, `/en/blog/ranka`

## Takeaways for Ursa Minor

- Clear funnel: Home -> Game -> Play mode -> Room or match.
- Shared link (/r/) is the core “play with a friend” mechanic; one link, no signup required (guest play).
- Same header from home, game page, and in-room keeps navigation consistent.
- Game page is the decision point: four play modes with distinct outcomes (friend, robot, tournament, random).

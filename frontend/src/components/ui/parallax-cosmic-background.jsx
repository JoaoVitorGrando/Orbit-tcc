import { useEffect, useState } from 'react'

/**
 * CosmicParallaxBg — fundo cósmico com estrelas em parallax e texto animado.
 *
 * Renderiza estrelas via box-shadow (centenas de pontos em um único elemento),
 * uma órbita com detritos em duas camadas de profundidade, o planeta (#earth,
 * definido em index.css) e o texto de marca com entrada escalonada.
 *
 * Props:
 *   head      — título principal (grande, centralizado). Padrão: 'Orbit RH'
 *   text      — subtítulo separado por vírgulas; cada parte entra em sequência.
 *               Até 4 partes têm atraso próprio; da 5ª em diante repete o da 4ª.
 *   loop      — se as animações de texto repetem (padrão: true)
 *   className — classes adicionais no container
 *
 * Observação: as animações são desativadas automaticamente para quem ativou
 * "reduzir movimento" no sistema operacional (ver index.css).
 */
export function CosmicParallaxBg({
  head = 'Orbit RH',
  text = 'Escuta,Desenvolvimento,Decisão',
  loop = true,
  className = '',
}) {
  const [starsUp, setStarsUp] = useState('')
  const [starsDown, setStarsDown] = useState('')
  const [starsLeft, setStarsLeft] = useState('')
  const [starsRight, setStarsRight] = useState('')
  const [starsLargeUp, setStarsLargeUp] = useState('')
  const [starsLargeDown, setStarsLargeDown] = useState('')
  const [brightLayers, setBrightLayers] = useState({
    up: '',
    down: '',
    left: '',
    right: '',
  })

  const textParts = String(text)
    .split(',')
    .map((p) => p.trim())
    .filter(Boolean)

  const orbitDebris = [
    { x: 5, y: 39, size: 2, tone: 'dim', layer: 'back', ox: -3, oy: 4, scale: 1.15 },
    { x: 13, y: 31, size: 3, tone: 'mid', layer: 'back', ox: 5, oy: -3, scale: 1.5 },
    { x: 19, y: 43, size: 2, tone: 'dim', layer: 'back', ox: -2, oy: 2, scale: 1.2 },
    { x: 28, y: 22, size: 4, tone: 'bright', layer: 'back', ox: 3, oy: -5, scale: 1.55 },
    { x: 37, y: 29, size: 2, tone: 'dim', layer: 'back', ox: -4, oy: 1, scale: 1.1 },
    { x: 45, y: 17, size: 2, tone: 'mid', layer: 'back', ox: 1, oy: -2, scale: 1.25 },
    { x: 53, y: 25, size: 3, tone: 'bright', layer: 'back', ox: 6, oy: 3, scale: 1.45 },
    { x: 60, y: 33, size: 2, tone: 'dim', layer: 'back', ox: -5, oy: -2, scale: 1.2 },
    { x: 66, y: 27, size: 2, tone: 'mid', layer: 'back', ox: 2, oy: 4, scale: 1.3 },
    { x: 75, y: 36, size: 3, tone: 'dim', layer: 'back', ox: -3, oy: -1, scale: 1.4 },
    { x: 85, y: 42, size: 2, tone: 'dim', layer: 'back', ox: 4, oy: 2, scale: 1.15 },
    { x: 95, y: 48, size: 2, tone: 'mid', layer: 'back', ox: -2, oy: -4, scale: 1.25 },
    { x: 42, y: 37, size: 2, tone: 'bright', layer: 'back', ox: -6, oy: 5, scale: 1.2 },
    { x: 7, y: 57, size: 2, tone: 'dim', layer: 'front', ox: -2, oy: -4, scale: 1.15 },
    { x: 16, y: 64, size: 3, tone: 'mid', layer: 'front', ox: 4, oy: 3, scale: 1.5 },
    { x: 23, y: 74, size: 2, tone: 'dim', layer: 'front', ox: -5, oy: 1, scale: 1.1 },
    { x: 33, y: 69, size: 2, tone: 'mid', layer: 'front', ox: 3, oy: -3, scale: 1.3 },
    { x: 40, y: 79, size: 4, tone: 'bright', layer: 'front', ox: -1, oy: 4, scale: 1.6 },
    { x: 49, y: 71, size: 2, tone: 'dim', layer: 'front', ox: 6, oy: -2, scale: 1.2 },
    { x: 55, y: 77, size: 3, tone: 'bright', layer: 'front', ox: -4, oy: 2, scale: 1.35 },
    { x: 65, y: 73, size: 2, tone: 'mid', layer: 'front', ox: 2, oy: -5, scale: 1.25 },
    { x: 73, y: 66, size: 2, tone: 'dim', layer: 'front', ox: -3, oy: 3, scale: 1.2 },
    { x: 83, y: 59, size: 3, tone: 'mid', layer: 'front', ox: 5, oy: -1, scale: 1.45 },
    { x: 93, y: 53, size: 2, tone: 'dim', layer: 'front', ox: -2, oy: 2, scale: 1.15 },
    { x: 38, y: 58, size: 2, tone: 'dim', layer: 'front', ox: 3, oy: 5, scale: 1.2 },
    { x: 78, y: 76, size: 2, tone: 'bright', layer: 'front', ox: -4, oy: -3, scale: 1.3 },
  ]

  function debrisStyle(d) {
    return {
      left: `${d.x}%`,
      top: `${d.y}%`,
      width: d.size,
      height: d.size,
      '--debris-ox': `${d.ox ?? 0}px`,
      '--debris-oy': `${d.oy ?? 0}px`,
      '--debris-scale': d.scale ?? 1.35,
    }
  }

  function generateStarBoxShadow(count) {
    const shadows = []
    for (let i = 0; i < count; i++) {
      const x = Math.floor(Math.random() * 2000)
      const y = Math.floor(Math.random() * 2000)
      shadows.push(`${x}px ${y}px #FFF`)
    }
    return shadows.join(', ')
  }

  function generateBrightStarBoxShadow(count) {
    const shadows = []
    for (let i = 0; i < count; i++) {
      const x = Math.floor(Math.random() * 2000)
      const y = Math.floor(Math.random() * 2000)
      shadows.push(
        `${x}px ${y}px 2px #fff, ${x}px ${y}px 22px rgba(255, 255, 255, 0.75), ${x}px ${y}px 40px rgba(190, 230, 255, 0.35)`,
      )
    }
    return shadows.join(', ')
  }

  function generateShootingStars(count) {
    const stamp = Date.now()

    const corners = [
      // superior-esquerdo → inferior-direita
      () => ({
        top: '-5vmin',
        left: '-5vmin',
        angle: 36 + Math.random() * 18,
      }),
      // superior-direito → inferior-esquerda
      () => ({
        top: '-5vmin',
        left: 'calc(100% + 3vmin)',
        angle: 126 + Math.random() * 18,
      }),
      // inferior-esquerdo → superior-direita
      () => ({
        top: 'calc(100% + 3vmin)',
        left: '-5vmin',
        angle: -54 + Math.random() * 18,
      }),
      // inferior-direito → superior-esquerda
      () => ({
        top: 'calc(100% + 3vmin)',
        left: 'calc(100% + 3vmin)',
        angle: -144 + Math.random() * 18,
      }),
    ]

    return Array.from({ length: count }, (_, i) => {
      const spawn = corners[Math.floor(Math.random() * corners.length)]()
      const cycle = 72 + Math.random() * 24
      const slot = cycle / count

      return {
        id: `${stamp}-${i}`,
        top: spawn.top,
        left: spawn.left,
        angle: spawn.angle,
        length: 32 + Math.floor(Math.random() * 24),
        distance: `${155 + Math.floor(Math.random() * 25)}vmax`,
        cycle,
        delay: -(i * slot + Math.random() * slot * 0.35),
      }
    })
  }

  const [shootingStars] = useState(() => generateShootingStars(3))

  useEffect(() => {
    setStarsUp(generateStarBoxShadow(300))
    setStarsDown(generateStarBoxShadow(250))
    setStarsLeft(generateStarBoxShadow(130))
    setStarsRight(generateStarBoxShadow(130))
    setStarsLargeUp(generateStarBoxShadow(55))
    setStarsLargeDown(generateStarBoxShadow(45))
    setBrightLayers({
      up: generateBrightStarBoxShadow(2),
      down: generateBrightStarBoxShadow(1),
      left: generateBrightStarBoxShadow(1),
      right: generateBrightStarBoxShadow(1),
    })
  }, [])

  return (
    <div
      className={`cosmic-parallax-container ${className}`}
      // Escopo local: a variável vale só dentro deste container e é herdada
      // pelos filhos. Antes era escrita em document.documentElement, o que
      // vazava estado global e persistia após a desmontagem do componente.
      style={{ '--animation-iteration': loop ? 'infinite' : '1' }}
    >
      {/* Camadas de estrelas — direções e velocidades distintas */}
      <div style={{ boxShadow: starsUp }} className="cosmic-stars cosmic-stars--up" />
      <div style={{ boxShadow: starsDown }} className="cosmic-stars cosmic-stars--down" />
      <div style={{ boxShadow: starsLeft }} className="cosmic-stars-medium cosmic-stars--left" />
      <div style={{ boxShadow: starsRight }} className="cosmic-stars-medium cosmic-stars--right" />
      <div style={{ boxShadow: starsLargeUp }} className="cosmic-stars-large cosmic-stars--up-slow" />
      <div style={{ boxShadow: starsLargeDown }} className="cosmic-stars-large cosmic-stars--down-slow" />
      <div style={{ boxShadow: brightLayers.up }} className="cosmic-stars-bright cosmic-stars-bright--up" />
      <div style={{ boxShadow: brightLayers.down }} className="cosmic-stars-bright cosmic-stars-bright--down" />
      <div style={{ boxShadow: brightLayers.left }} className="cosmic-stars-bright cosmic-stars-bright--left" />
      <div style={{ boxShadow: brightLayers.right }} className="cosmic-stars-bright cosmic-stars-bright--right" />

      {/* Estrelas cadentes — rápidas, atravessam a tela com rastro */}
      <div className="shooting-stars-layer" aria-hidden="true">
        {shootingStars.map((star) => (
          <span
            key={star.id}
            className="shooting-star"
            style={{
              top: star.top,
              left: star.left,
              '--shoot-angle': `${star.angle}deg`,
              '--shoot-length': `${star.length}px`,
              '--shoot-distance': star.distance,
              '--shoot-cycle': `${star.cycle}s`,
              animationDelay: `${star.delay}s`,
            }}
          />
        ))}
      </div>

      {/* Órbita com detritos — camadas separadas para profundidade */}
      <div className="orbit-layer orbit-layer-back" aria-hidden="true">
        <div className="orbit-track" />
        {orbitDebris
          .filter((d) => d.layer === 'back')
          .map((d, i) => (
            <span
              key={`back-${i}`}
              className={`orbit-debris orbit-debris--${d.tone}`}
              style={debrisStyle(d)}
            />
          ))}
      </div>

      <div id="earth" />

      <div className="orbit-layer orbit-layer-front" aria-hidden="true">
        <div className="orbit-track orbit-track-front" />
        {orbitDebris
          .filter((d) => d.layer === 'front')
          .map((d, i) => (
            <span
              key={`front-${i}`}
              className={`orbit-debris orbit-debris--${d.tone}`}
              style={debrisStyle(d)}
            />
          ))}
      </div>

      {/* Título e subtítulo */}
      <div id="title">{head.toUpperCase()}</div>
      <div id="subtitle">
        {textParts.map((part, index) => (
          <span key={index} className={`subtitle-part-${index + 1}`}>
            {part.toUpperCase()}
            {index < textParts.length - 1 ? ' ' : ''}
          </span>
        ))}
      </div>
    </div>
  )
}

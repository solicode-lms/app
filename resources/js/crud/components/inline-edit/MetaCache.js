// Ce fichier est maintenu par ESSARRAJ Fouad

/**
 * MetaCache
 * - Clé = (entityType, id, field)
 * - Stockage en mémoire avec TTL
 * - Utilisé par CellOrchestrator pour charger rapidement les metas
 */
class MetaCache {
    constructor() {
        this.cache = new Map(); // clé -> { meta, expire }
        this.ttl = 5 * 60 * 1000; // 5 minutes
    }

    _key(entityType, id, field) {
        return `${entityType}:${id}:${field}`;
    }

    /**
     * Retourne une meta si présente et valide
     */
    get(entityType, id, field) {
        const key = this._key(entityType, id, field);
        const entry = this.cache.get(key);
        if (entry && entry.expire > Date.now()) {
            return entry.meta;
        }
        return null;
    }

    /**
     * Définit une meta dans le cache
     */
    set(entityType, id, field, meta) {
        const key = this._key(entityType, id, field);
        this.cache.set(key, {
            meta,
            expire: Date.now() + this.ttl,
        });
    }

    /**
     * Récupère une meta (cache ou API)
     */
    async getMeta(entityType, id, field) {
        const cached = this.get(entityType, id, field);
        if (cached) return cached;

        // Sinon → requête API
        const res = await fetch(
            `/admin/PkgRealisationTache/realisationTaches/${id}/field/${field}/meta`
        );

        if (!res.ok) {
            throw new Error(`Erreur API meta: ${res.status}`);
        }

        const meta = await res.json();
        this.set(entityType, id, field, meta);
        return meta;
    }
}

// Instance unique exportée
export const metaCache = new MetaCache();

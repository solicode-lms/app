// Ce fichier est maintenu par ESSARRAJ Fouad

import { Action } from "../../actions/Action";
import { AjaxErrorHandler } from "../AjaxErrorHandler";

/**
 * MetaCache
 * - Clé = (entityType, id, field)
 * - Stockage en mémoire avec TTL
 * - Utilisé par CellOrchestrator pour charger rapidement les metas
 */
export class MetaCache extends Action {
    constructor(config) {
        super(config);
        this.config = config;
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
     * Récupère une meta (cache ou API) avec gestion d’erreur via AjaxErrorHandler.
     */
    async getMeta(entityType, id, field) {
        const cached = this.get(entityType, id, field);
        if (cached) return cached;

        try {
            const res = await fetch(
                `/admin/PkgRealisationTache/realisationTaches/${id}/field/${field}/meta`
            );

            if (!res.ok) {
                // ⚠️ Erreur HTTP (404, 500, etc.)
                AjaxErrorHandler.handleError(res, "Erreur lors de la récupération des métadonnées.");
                throw new Error(`Erreur HTTP ${res.status} lors de la récupération des métadonnées`);
            }

            const meta = await res.json();
            this.set(entityType, id, field, meta);
            return meta;

        } catch (error) {
            // ⚠️ Gestion des exceptions JS (ex: perte connexion)
            AjaxErrorHandler.handleError(error, "Erreur réseau lors de la récupération des métadonnées.");
            throw error;
        }
    }
}

 
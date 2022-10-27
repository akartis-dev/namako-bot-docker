import { Injectable } from '@nestjs/common';
import { YoutubeResultItem } from '../interface/youtube.interface';
const api = require('ytsr');

@Injectable()
export class YoutubeService {
  async searchInYoutube(searchKey: string, type: string) {
    if (searchKey.length > 1) {
      const result = await api(searchKey, {
        gl: 'fr',
        hl: 'fr',
        limit: 15,
      });

      const formatedResult: Array<YoutubeResultItem> = [];
      result.items.map((e, i) => {
        const item: YoutubeResultItem = {
          thumbnails: 'https://place-hold.it/300x500',
          url: '',
          duration: '',
          title: '',
        };

        if (e?.title && e?.url && e?.duration) {
          if (e?.thumbnails && e?.thumbnails?.length > 0) {
            item.thumbnails = e?.thumbnails[0]?.url;
          } else {
            if (e?.bestThumbnail) {
              item.thumbnails = e?.bestThumbnail?.url;
            }
          }

          item.title = e?.title?.replace('.', '_')?.substring(0, 80);
          item.url = e?.url;
          item.duration = e?.duration;

          if (type === 'mp3' && this.explodeDuration(item.duration) < 1100) {
            formatedResult.push(item);
          }

          if (type === 'mp4' && this.explodeDuration(item.duration) < 13000) {
            formatedResult.push(item);
          }
        }
      });

      return formatedResult.slice(0, 8);
    }

    return { error: true, message: 'Le terme pour recherche est trop court' };
  }

  /**
   * Explode duration from 00:00 to 0000
   * @param {string} duration
   * @private
   */
  private explodeDuration(duration: string): number {
    const splitted = duration.split(':');

    return Number(`${splitted[0]}${splitted[1]}${splitted[2] ?? ''}`);
  }
}

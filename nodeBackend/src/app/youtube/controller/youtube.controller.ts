import { Controller, Get, Query } from '@nestjs/common';
import { YoutubeService } from '../service/youtube.service';

@Controller('youtube')
export class YoutubeController {
  constructor(private youtubeSrv: YoutubeService) {}

  @Get()
  async youtubeSearch(@Query('q') search, @Query('type') type = '') {
    return await this.youtubeSrv.searchInYoutube(search, type);
  }
}

import { Module } from '@nestjs/common';
import { AppController } from './app.controller';
import { AppService } from './app.service';
import {YoutubeModule} from "./app/youtube/youtube.module";

@Module({
  imports: [
    YoutubeModule
  ],
  controllers: [AppController],
  providers: [AppService],
})
export class AppModule {}

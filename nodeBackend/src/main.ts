import { NestFactory } from '@nestjs/core';
import { AppModule } from './app.module';

async function bootstrap() {
  const app = await NestFactory.create(AppModule);
  const port = process.env.NODE_ENV === 'prod' ? 3000 : 4000;
  console.log('Listen in port ' + port);
  console.log('process.env.PRODUCTION ' + process.env.NODE_ENV);
  await app.listen(port); // port 3000 for production, 4000 for dev
}
bootstrap();

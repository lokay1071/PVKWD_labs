import {
  Column,
  CreateDateColumn,
  Entity,
  PrimaryGeneratedColumn,
  UpdateDateColumn,
} from 'typeorm';
import { ApiProperty } from '@nestjs/swagger';

@Entity('products')
export class Product {
  @ApiProperty({
    example: 1,
    description: 'The unique identifier of the product',
  })
  @PrimaryGeneratedColumn()
  id: number;

  @ApiProperty({
    example: 'Product Name',
    description: 'The name of the product',
  })
  @Column()
  name: string;

  @ApiProperty({
    example: 'Product description',
    description: 'A brief description of the product',
  })
  @Column()
  description: string;

  @ApiProperty({ example: 19.99, description: 'The price of the product' })
  @Column('decimal')
  price: number;

  @ApiProperty({
    example: 'image.jpg',
    description: 'The URL of the product image',
  })
  @Column()
  image: string;

  @ApiProperty({
    description: 'The category of the product',
  })
  @Column()
  categoryId: number;

  @ApiProperty({
    example: '2023-01-01T12:34:56.789Z',
    description: 'The creation date of the product',
  })
  @CreateDateColumn({
    type: 'timestamp',
    default: () => 'CURRENT_TIMESTAMP(6)',
  })
  public created_at: Date;

  @ApiProperty({
    example: '2023-01-01T12:34:56.789Z',
    description: 'The last update date of the product',
  })
  @UpdateDateColumn({
    type: 'timestamp',
    default: () => 'CURRENT_TIMESTAMP(6)',
    onUpdate: 'CURRENT_TIMESTAMP(6)',
  })
  public updated_at: Date;
}
